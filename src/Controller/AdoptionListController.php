<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdoptionListController extends AbstractController
{
    /**
     * @Route("/adoption_list", name="adoption_list")
     */
    public function index(): Response
    {
        return $this->render('adoption_list/index.html.twig', [
            'controller_name' => 'AdoptionListController',
        ]);
    }
}
