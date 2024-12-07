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
        $sessionId = $request->getSession()->getId();
        $cartItems = $this->em->getRepository(Order::class)->findBy(['order_id' => $sessionId]);
        $flowers = [];
        foreach ($cartItems as $item) {
            $flower = $this->em->getRepository(Flower::class)->find($item->getFlowerId());
            $flowers[$item->getId()] = $flower; 
        }
        return $this->render('cart.html.twig', [
            'cartItems' => $cartItems,
            'flowers' => $flowers, 
        ]);
    }

    #[Route(path:"/add_to_cart/{id}", name:"add_to_cart")]
    public function addToCart($id, Request $request): RedirectResponse
    {
        $flower = $this->em->getRepository(Flower::class)->find($id);

        if ($flower) {
            $sessionId = $request->getSession()->getId();
            $cartItem = $this->em->getRepository(Order::class)->findOneBy([
                'order_id' => $sessionId,
                'flower_id' => $flower->getId(),
            ]);
            if (!$cartItem) {
                $cartItem = new Order();
                $cartItem->setOrderId($sessionId);
                $cartItem->setFlowerId($flower->getId());
                $cartItem->setAmount(0); 
                $cartItem->setStatus(1);
            }
            $this->em->persist($cartItem);
            $this->em->flush();
        }

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
