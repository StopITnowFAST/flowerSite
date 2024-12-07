<?php

namespace App\Controller;

use App\Entity\Flower;
use App\Entity\Order;
use App\Form\FlowerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController {

    private $em;
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // Страница с цветами
    #[Route(path: '/admin/flower', name: 'flower_list')] 
    function flowers(Request $request) {
        $flowers = $this->em->getRepository(Flower::class)->findAll();

        $flower = new Flower();
        $form = $this->createForm(FlowerFormType::class, $flower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($flower);
            $this->em->flush();
            $this->addFlash('success', 'Цветок успешно добавлен!');
            return $this->redirectToRoute('flower_list');
        }

        return $this->render('admin/flowers.html.twig', [
            'flowers' => $flowers,
            'form' => $form->createView(),
        ]);
    }

    // Страница редактирования карточек товара
    #[Route(path: '/admin/cards')] 
    function cards() {
        return new Response('Success');
    }

    // Страница просмотра заказов
    #[Route(path: '/admin/orders')] 
    function orders() {
        $orders = $this->em->getRepository(Order::class)->findAll();
        $flowers = $this->em->getRepository(Flower::class)->findAll();
        $flowerNames = [];
        foreach ($flowers as $flower) {
            $flowerNames[$flower->getId()] = $flower->getName();
        }
        
        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
            'flowerNames' => $flowerNames,
        ]);
    }

    // Страница редактирования склада
    #[Route(path: '/admin/storage')] 
    function storage() {
        return new Response('Success');
    }

    #[Route('/admin/flower/delete/{id}', name: 'flower_delete', methods: ['POST'])]
    public function delete(int $id): RedirectResponse
    {
        $flower = $this->em->getRepository(Flower::class)->find($id);

        if ($flower) {
            $this->em->remove($flower);
            $this->em->flush();
            $this->addFlash('success', 'Flower deleted successfully.');
        } else {
            $this->addFlash('error', 'Flower not found.');
        }

        return $this->redirectToRoute('flower_list');
    }
}