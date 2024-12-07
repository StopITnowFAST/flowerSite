<?php

namespace App\Controller;

use App\Entity\Flower;
use App\Entity\Order;
use App\Entity\Storage;
use App\Form\FlowerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\StorageFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class CatalogController extends AbstractController {

    private $em;
    private $logger;
    function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
    ) {
        $this->em = $em;
        $this->logger = $logger;        
    }

    // Страница с цветами
    #[Route(path: '/catalog', name: 'catalog')] 
    function catalog() {
        $flowers = $this->em->getRepository(Flower::class)->findAll();

        return $this->render('catalog.html.twig', [
            'flowers' => $flowers,
        ]);
    }
}