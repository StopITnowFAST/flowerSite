<?php

namespace App\Controller;

use App\Entity\Flower;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $em;
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route(path:"/cart", name:"view_cart")]
    public function viewCart(Request $request): Response
    {
        // Получаем уникальный идентификатор сессии
        $sessionId = $request->getSession()->getId();

        // Извлекаем корзину для данного пользователя (по session_id)
        $cartItems = $this->em->getRepository(Order::class)->findBy(['order_id' => $sessionId]);

        // Создаем массив для хранения информации о цветках
        $flowers = [];

        // Загружаем данные о цветках
        foreach ($cartItems as $item) {
            $flower = $this->em->getRepository(Flower::class)->find($item->getFlowerId());
            $flowers[$item->getId()] = $flower; // Записываем цветок для каждого заказа
        }

        return $this->render('cart.html.twig', [
            'cartItems' => $cartItems,
            'flowers' => $flowers, // Передаем данные о цветках
        ]);
    }

    #[Route(path:"/add_to_cart/{id}", name:"add_to_cart")]
    public function addToCart($id, Request $request): RedirectResponse
    {
        // Получаем товар из базы данных по ID
        $flower = $this->em->getRepository(Flower::class)->find($id);

        if ($flower) {
            // Получаем уникальный идентификатор сессии
            $sessionId = $request->getSession()->getId();
            
            // Проверяем, есть ли уже такой товар в корзине для этого пользователя (по session_id)
            $cartItem = $this->em->getRepository(Order::class)->findOneBy([
                'order_id' => $sessionId,
                'flower_id' => $flower->getId(),
            ]);

            if ($cartItem) {
                // Если товар уже в корзине, увеличиваем количество
                $cartItem->setAmount($cartItem->getAmount() + 1);
            } else {
                // Если товара нет в корзине, создаем новую запись
                $cartItem = new Order();
                $cartItem->setOrderId($sessionId);
                $cartItem->setFlowerId($flower->getId());
                $cartItem->setAmount(1); // Устанавливаем начальное количество товара
                $cartItem->setStatus(1);
            }

            // Сохраняем изменения
            $this->em->persist($cartItem);
            $this->em->flush();
        }

        // Перенаправляем обратно на страницу каталога
        return $this->redirectToRoute('catalog');
    }

    #[Route(path:"/remove_from_cart/{id}", name:"remove_from_cart")]
    public function removeFromCart($id, Request $request): RedirectResponse
    {
        // Получаем уникальный идентификатор сессии
        $sessionId = $request->getSession()->getId();

        // Ищем товар в корзине
        $cartItem = $this->em->getRepository(Order::class)->findOneBy([
            'order_id' => $sessionId,
            'flower_id' => $id,
        ]);

        if ($cartItem) {
            // Удаляем товар из корзины
            $this->em->remove($cartItem);
            $this->em->flush();
        }

        // Перенаправляем обратно на страницу корзины
        return $this->redirectToRoute('view_cart');
    }
}
