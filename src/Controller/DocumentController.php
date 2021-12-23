<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Dossier;
use App\Form\DocumentType;
use App\Repository\DossierRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    /**
     * @Route("/documents", name="documents")
     */
    public function index(): Response
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    /**
     * @Route("/documents/{id}", name="procedureDoc")
     */
    public function procedure(Dossier $dos, Request $request, ManagerRegistry $mr, DossierRepository $repo): Response
    {
        $dossier = $repo->createQueryBuilder('d');
        $dossier->select('d', 'u', 'a')
            ->join('d.user', 'u')
            ->join('d.animal', 'a')
            ->where('d.id = :id')
        ->setParameter('id', $dos->getId());
        $dossier = $dossier->getQuery()->getOneOrNullResult();

        $user = $dossier->getUser();
        $animal = $dossier->getAnimal();
        $documents = new Documents();


        $form = $this->createForm(DocumentType::class, $documents);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cni = $form->get('cni')->getData();
            $cniName = md5(uniqid()).'.'. $cni->guessExtension();
            $cni->move(
                $this->getParameter('upload_file_pdf'),
                $cniName
            );
            $documents->setCni($cniName);

            $justDom = $form->get('justif_dom')->getData();
            $justDomName = md5(uniqid()).'.'. $justDom->guessExtension();
            $justDom->move(
                $this->getParameter('upload_file_pdf'),
                $justDomName
            );
            $documents->setJustifDom($justDomName);
            $documents->setDossier($dos);




            $em = $mr->getManager();
            $em->persist($documents);
            $em->flush();

            $dos->setStatut('En cours d\'examen');
            $em->persist($documents);
            $em->flush();

            $this->addFlash("success", "Votre procédure a bien été enregistrée");

            // On retourne sur la page d'accueil
            return $this->redirectToRoute("dossiers");
        }

        return $this->render('document/procedure.html.twig', [
            'form' => $form->createView(),
            'user' =>$user,
            'animal' =>$animal
        ]);
    }
}
