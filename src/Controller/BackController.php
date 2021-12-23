<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BackController extends AbstractController
{
    /**
     * @Route("/back/list/produits", name="back_list_produits")
     */
    public function getListProduitsEdit(Security $security, ProduitRepository $repo): Response
    {
        $user = $security->getUser();
       
        if($user !== null && $user->getAdmin() !== true) {
            return $this->redirectToRoute('index');
        }
        elseif($user === null) {
            return $this->redirectToRoute('index');
        }

        $produits = $repo->findAll();

        return $this->render('back/listProduits.html.twig', [
            'produits' => $produits,
        ]);
    }


    /**
     * @Route("/back/produit/create", name="back_produit_create")
     */
    public function createProduit(Request $request, EntityManagerInterface $manager) {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash("success", "Votre article a bien été créé");
            
            return $this->redirectToRoute('back_produit_edit', ['id' => $produit->getId()]);
        }

        return $this->render('back/createProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/back/produit/{id}/edit", name="back_produit_edit")
     */
    public function editProduit(Security $security, Produit $produit, Request $request, EntityManagerInterface $manager) {
        $user = $security->getUser();
       
        if($user !== null && $user->getAdmin() !== true) {
            return $this->redirectToRoute('index');
        }
        elseif($user === null) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash("success", "L'article a bien été modifié");

            return $this->redirectToRoute('back_list_produits');
        }

        return $this->render('back/editProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/back/produit/{id}/delete", name="back_produit_delete")
     */
    public function deleteProduit(Produit $produit, Request $request, EntityManagerInterface $manager) {

        $delete = $manager->createQuery('DELETE FROM App\Entity\Produit p WHERE p.id = :id');
        $delete->setParameter('id', $produit->getId());
        $deleted = $delete->getResult();

        $this->addFlash("success", "L'article a bien été supprimé");

        return $this->redirectToRoute('back_list_produits');
    }
}
