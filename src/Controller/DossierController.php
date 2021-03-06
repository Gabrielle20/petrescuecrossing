<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Animal;
use App\Entity\Dossier;

use App\Form\DossierType;
use App\Form\ValidateDossierType;
use App\Controller\SecurityController;
use DeprecationTests\Foo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        //si l'utilisateut n'est pas connecté on le redirige vers la connexion
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }

        //si l'utilisateur est administrateur
        else if($user->getAdmin() == true)
        {
            //on récupère tous les dossiers de la table
            $dossiers = $this->getDoctrine()->getRepository(Dossier::class)->findAll(); 

            return $this->render('dossier/index.html.twig', [
                'dossiers' => $dossiers,
                'admin' => 1
            ]);
        }
        else {
            //sinon on recupère uniquement les dossiers concernant l'utilisateur connecté
            $dossiers = $this->getDoctrine()->getRepository(Dossier::class)->findBy(['user' => $user]); 

            return $this->render('dossier/index.html.twig', [
                'dossiers' => $dossiers,
                'admin' => 0
            ]);
        }
    }

    /**
     * @Route("/dossier/{id}/save", name="dossier_save")
     */
    public function save(Request $request, ManagerRegistry $doctrine, Animal $animal): Response
    {
        $user = $this->getUser(); 
        
        //si pas connecté
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }
        //si connecté
        else {
            //on créé un dossier
            $dossier = new Dossier; 

            //on revoie le formulaire pour ajouter un dossier
            $form = $this->createForm(
                DossierType::class, $dossier); 
            $form->handleRequest($request); 

            if($form->isSubmitted() && $form->isValid())
            {

                $dossier->setAnimal($animal);
                $dossier->setStatut("En attente");
                $dossier->setUser($user);

                $dossier->setDate(new \DateTime());


                
                $em = $doctrine->getManager(); 
                $em->persist($dossier); 
                $em->flush(); 

                $this->addFlash("success", "Dossier enregistré ! Votre demande a bien était prise en compte.");

                return $this->redirectToRoute("dossiers");
            }

        }
        return $this->render("dossier/save.html.twig", [
             'form' => $form->createView(),
             'animal' => $animal,
         ]);
    }
    /**
     * @Route("/dossier/changeStatus/{id}", name="changeStatus")
     */
    public function changeStatus(Dossier $dossier,Request $request, ManagerRegistry $mr, MailerInterface $mailer)
    {
        $status = $request->request->get('status');

        $em = $mr->getManager();
        $dossier->setStatut($status);
        $em->persist($dossier);
        $em->flush();

        if($status ==  "En cours d'examen"){
            (new EmailController())->sendMail($mailer, $dossier->getUser(), $dossier->getId(),'adoptRequest');
        }
        if($status ==  "Demande refusée"){
            (new EmailController())->sendMail($mailer, $dossier->getUser(),$dossier->getAnimal(), $dossier->getId(),'requestRefused');
        }
        if($status ==  "Demande validée"){
            $animal = $dossier->getAnimal();
            $animal->setIsGiven("1");
            $em->persist($animal);
            $em->flush();

            (new EmailController())->sendMail($mailer, $dossier->getUser(),$dossier->getAnimal(), $dossier->getId(),'requestValid');
        }


        $this->addFlash("success",'Statut changé avec succès');
        return $this->redirectToRoute('dossiers');

    }

    /**
     * @Route("/dossier/single/{id}", name="dossier_single")
     */

    public function single(Dossier $dossier,ManagerRegistry $mr)
    {
        $user = $this->getUser();
        if($user == null) {
            return $this->redirectToRoute('index');
        }
        if($user != null && $user->getAdmin() !== true) {
            return $this->redirectToRoute('index');
        }
        $admin = $user->getAdmin();
        $document = isset($this->getDoctrine()->getRepository(Documents::class)->findBy(['dossier' => $dossier])[0]) ? $this->getDoctrine()->getRepository(Documents::class)->findBy(['dossier' => $dossier])[0] : null ;

        //si pas connecté
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }
        //si connecté 
        else {
            return $this->render("dossier/single.html.twig", [
                "dossier" => $dossier, 
                "animal" => $dossier->getAnimal(), 
                "admin" => $admin,
                'document' => $document
            ]); 
        }
    }

    /**
     * @Route("/dossier/delete/{id}", name= "dossier_delete")
     */
    public function delete(Dossier $dossier, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser(); 

        //si pas connecté
        if($user == null)
        {
            return $this->redirectToRoute("security_connexion");
        }
        //si connecté
        else {
            $em = $doctrine->getManager();
            $em->remove($dossier);
            $em->flush();
            $this->addFlash("success", "Dossier supprimé ");
            return $this->redirectToRoute("dossiers");
        }
    }
}
