<?php

namespace App\Controller;


use App\Entity\Animal;
use App\Form\EditAnimalType;
use App\Form\SaveAnimalType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{


    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }

    /**
     * @Route("/animal/animal_list", name="animaList")
     */
    public function list(): Response
    {
        $animals = $this->getDoctrine()->getRepository(Animal::class)->findAll();
        return $this->render('animal/list.html.twig', [
            'animals' => $animals
        ]);
    }


    /**
     * @Route("/animal/save", name="addAnimal")
     */
    public function save(Request $request, ManagerRegistry $mr): Response
    {
        $animal = new Animal();

        $form = $this->createForm(SaveAnimalType::class, $animal);

        $form->handleRequest($request);

        // Si les données sont ok
        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            $pictureName = md5(uniqid()).'.'. $picture->guessExtension();

            $picture->move(
            // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
            // de config services.yaml
                $this->getParameter('upload_file'),
                $pictureName
            );
            $animal->setPicture($pictureName);


            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($animal);
            $em->flush();

            // On génère un message flash qui apparaîtra sur la page d'accueil pour valider l'enregistrement
            // de l'article auprès de l'utilisateur
            $this->addFlash("success", "Animal bien enregistré");

            // On retourne sur la page d'accueil
            return $this->redirectToRoute("animaList");
        }
        // On charge le template save en lui passant le formulaire dont on a besoin
        // Attention le formulaire est toujours passé avec ->createView()
        return $this->render("animal/save.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/animal/edit/{id}", name="editAnimal")
     */
    public function edit(Animal $animal, ManagerRegistry $mr, Request $request): Response
    {
        $oldPicName = $animal->getPicture();

        if(file_exists($this->getParameter('upload_file')."/".$animal->getPicture())){
            $picture = new File($this->getParameter('upload_file')."/".$animal->getPicture());
        }
        else{
            $picture = new File($this->getParameter('upload_file')."/notfounddog.png");
        }
        $animal->setPicture($picture);
        $form = $this->createForm(EditAnimalType::class, $animal);



        $form->handleRequest($request);

        // Si les données sont ok
        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            $pictureName= $oldPicName;
            if($picture && $picture != null){
                $pictureName = md5(uniqid()).'.'. $picture->guessExtension();

                $picture->move(
                // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
                // de config services.yaml
                    $this->getParameter('upload_file'),
                    $pictureName
                );

            }
            $animal->setPicture($pictureName);

            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($animal);
            $em->flush();

            // On génère un message flash qui apparaîtra sur la page d'accueil pour valider l'enregistrement
            // de l'article auprès de l'utilisateur
            $this->addFlash("success", "Modifications bien enregistrées");

            // On retourne sur la page d'accueil
            return $this->redirectToRoute("animaList");
        }
        // On charge le template save en lui passant le formulaire dont on a besoin
        // Attention le formulaire est toujours passé avec ->createView()
        return $this->render("animal/edit.html.twig", [
            'form' => $form->createView(),
            "animal" => $animal
        ]);

    }

    /**
     * @Route("/animal/delelte/{id}", name="deletAnimal")
     */
    public function delete(Animal $animal): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($animal);
        $em->flush();

        $this->addFlash("success", "L'animal a bién été supprimé");
        return $this->redirectToRoute("animaList");
    }



    /**
     * @Route("/animal/single/{id}", name="singlAnimal")
     */
    public function showSingle(Animal $animal): Response
    {
        return $this->render('animal/single.html.twig', [
            'animal' => $animal
        ]);
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil()
    {
        return $this->render('accueil.html.twig'); 
    }
}
