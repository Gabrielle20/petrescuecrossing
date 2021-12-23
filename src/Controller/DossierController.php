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
use App\Controller\SecurityController;

//  /**
//  * @Route("/dossier")
//  * IsGranted("ROLE_USER")
//  */
class DossierController extends AbstractController
{
    /**
     * @Route("/dossiers", name="dossiers")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }
        else {
            $dossiers = $this->getDoctrine()->getRepository(Dossier::class)->findBy(['user' => $user]); 

            return $this->render('dossier/index.html.twig', [
                'dossiers' => $dossiers,
            ]);
        }
    }

    /**
     * @Route("/dossier/save", name="dossier_save")
     */
    public function save(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser(); 
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }
        else {
            $dossier = new Dossier; 
            $form = $this->createForm(
                DossierType::class, $dossier); 
            $form->handleRequest($request); 
            if($form->isSubmitted() && $form->isValid())
            {
                $dossier->setStatut(1); 
                $dossier->setUser($user); 

                $em = $doctrine->getManager(); 
                $em->persist($dossier); 
                $em->flush(); 

                $this->addFlash("success", "Dossier enregistré ! ");

                return $this->redirectToRoute("dossiers");
            }

        }
        // return $this->render("dossier/save.html.twig", [
        //     'form' => $form->createView(), 
        // ]); 
    }

    /**
     * @Route("/dossier/single/{id}", name="dossier_single")
     */
    public function single(Dossier $dossier)
    {
        return $this->render("dossier/single.html.twig", [
            "dossier" => $dossier, 
            "animal" => $dossier->getAnimal()
        ]); 
    }
}
