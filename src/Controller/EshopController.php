<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EshopController extends AbstractController
{
    /**
     * @Route("/eshop", name="eshop")
     */
    public function index(ProduitRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('eshop/index.html.twig', [
            'controller_name' => 'EshopController',
            'articles' => $articles,
        ]);
    }
}
