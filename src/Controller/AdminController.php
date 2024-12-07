<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController {
    // Страница с цветами
    #[Route(path: '/flowers')] 
    function flowers() {
        return new Response('Success');
    }

    // Страница редактирования карточек товара
    #[Route(path: '/flowerCard')] 
    function flowerCard() {
        return new Response('Success');
    }

    // Страница просмотра заказов
    #[Route(path: '/orders')] 
    function orders() {
        return new Response('Success');
    }

    // Страница редактирования склада
    #[Route(path: '/storage')] 
    function storage() {
        return new Response('Success');
    }
}