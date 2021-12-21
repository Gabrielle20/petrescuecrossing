<?php

namespace App\Controller;


use App\Entity\Animal;
use App\Form\SaveAnimalType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/animal/save", name="addAnimal")
     */
    public function save(Request $request, ManagerRegistry $mr): Response
    {
        $animal = new Animal();

        $form = $this->createForm(SaveAnimalType::class, $animal);

        $form->handleRequest($request);

        // Si les données sont ok
        /*if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            dump($picture);
            $pictureName = md5(uniqid()).'.'. $picture->guessExtension();

            $picture->move(
            // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
            // de config services.yaml
                $this->getParameter('upload_file'),
                $pictureName
            );
            $post->setPicture($pictureName);


            // On ajoute le createdAt à l'article
            $post->setCreatedAt(new \DateTime());
            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($post);
            $em->flush();

            // On génère un message flash qui apparaîtra sur la page d'accueil pour valider l'enregistrement
            // de l'article auprès de l'utilisateur
            $this->addFlash("success", "Article bien enregistré");

            // On retourne sur la page d'accueil
            return $this->redirectToRoute("category_list");
        }*/
        // On charge le template save en lui passant le formulaire dont on a besoin
        // Attention le formulaire est toujours passé avec ->createView()
        return $this->render("animal/save.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
