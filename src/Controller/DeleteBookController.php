<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteBookController extends AbstractController
{
    #[Route('/delete/book', name: 'app_delete_book')]
    public function index(): Response
    {
        return $this->render('delete_book/index.html.twig', [
            'controller_name' => 'DeleteBookController',
        ]);
    }
}
