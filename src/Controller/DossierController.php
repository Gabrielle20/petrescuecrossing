<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Animal;
use App\Form\DossierType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DossierController extends AbstractController
{
    /**
     * @Route("/dossier", name="dossier")
     */
    public function index(): Response
    {
        return $this->render('dossier/index.html.twig', [
            'controller_name' => 'DossierController',
        ]);
    }

    /**
     * @Route("/dossier/save", name="dossier_save")
     */
    public function save(Request $request, ManagerRegistry $doctrine): Response
    {
        $dossier = new Dossier; 
        $form = $this->createForm(
            DossierType::class, $dossier); 
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid())
        {
            $dossier->setStatut(1); 
            $dossier->setUser(1); 

            $em = $doctrine->getManager(); 
            $em->persist($dossier); 
            $em->flush(); 

            $this->addFlash("success", "Dossier enregistré ! ");

            return $this->redirectToRoute("dossier_save");
        }

        return $this->render("dossier/save.html.twig", [
            'form' => $form->createView()
        ]); 
    }
}
