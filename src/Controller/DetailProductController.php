<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailProductController extends AbstractController
{
    /**
     * @Route("/detail_product/{id}", name="detail_product")
     */
    public function index(Produit $produit, ProduitRepository $repo): Response
    {
        $allDetailArticles = $repo->findAll();

        return $this->render('detail_product/index.html.twig', [
            'controller_name' => 'DetailProductController',
            'detailArticles' => $produit,
            'allDetailArticles' => $allDetailArticles,
        ]);
    }
}
