<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\ProduitsPanier;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\DocumentsRepository;
use App\Repository\DossierRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\ProduitsPanierRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_account")
     */
    public function showAccount(User $user): Response
    {
        return $this->render('user/account.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/user/{id}/edit", name="user_account_edit")
     */
    public function editAccount(User $user, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été modifié !");

            return $this->redirectToRoute('user_account', ['id' => $user->getId()]);
        }

        return $this->render('user/editAccount.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/user/{id}/commandes", name="user_commandes")
     */
    public function getAllCommandes(User $user, PanierRepository $repo) {
        
        // $commandes = $repo->findBy(['user_id' => $user]);
        
        $commandes = $repo->createQueryBuilder('c');
        $commandes->select('c', 'p')
                  ->join('c.produits', 'p')
                  ->where('c.user_id = :user')
                  ->orderBy('c.date', 'DESC')
                  ->setParameter('user', $user);
        $commandes = $commandes->getQuery()->getResult();


        return $this->render('user/commandes.html.twig', [
            'user' => $user,
            'commandes' => $commandes
        ]);
    }


    /**
     * @Route("/user/{id}/dossiers", name="user_dossiers")
     */
    public function getAllDossiers(User $user, DossierRepository $repo, DocumentsRepository $doc) {
        $dossiers = $repo->findBy(['user' => $user]);

        $documentsDossier = [];
        foreach($dossiers as $dossier) {
            $documents = $doc->findBy(['dossier' => $dossier]);

            $documentsDossier[] = $documents;
        }

        
        return $this->render('user/dossiers.html.twig', [
            'user' => $user,
            'dossiers' => $dossiers
        ]);
    }


    /**
     * @Route("/add-to-cart/{id}", name="ajout_panier")
     */
    public function addToCart(Produit $produit, Security $security, ProduitRepository $articlesRepo, PanierRepository $repo, Request $request, EntityManagerInterface $manager) {
        $user = $security->getUser();

        $articles = $articlesRepo->findAll();

        // vérifie s'il y a un panier en cours pour cet utilisateur
        $panier = $repo->createQueryBuilder('p');
        $panier->select('p')
               ->where('p.user_id = :user')
               ->andwhere('p.statut = :statut')
               ->setParameters(['user' => $user, 'statut' => false]);
        $panier = $panier->getQuery()->getOneOrNullResult();
        
        $produitAjout = new ProduitsPanier();
        $produitAjout->setPrix($produit->getPrix());


        // gestion du panier
        if ($panier === null) {
            $panier = new Panier();
            $panier->setUserId($user);
            $panier->addProduit($produitAjout);
            $panier->setTotal($produit->getPrix());
            $panier->setDate(new DateTime());
            $panier->setStatut(false);
            $produitAjout->setPanier($panier);
            $produitAjout->setProduit($produit);
        }
        else {
            $produitAjout->setPanier($panier);
            $produitAjout->setProduit($produit);
            $ancienTotal = $panier->getTotal();
            $nouveauTotal = $ancienTotal + $produit->getPrix();
            $panier->addProduit($produitAjout);
            $panier->setTotal($nouveauTotal);
        }


        $manager->persist($panier);
        $manager->flush();

        $this->addFlash("success", "L'article a bien été ajouté à votre panier");

        return $this->redirectToRoute('eshop');
    }


    /**
     * @Route("/panier", name="panier")
     */
    public function getPanier(PanierRepository $repo) {
        $user = $this->getUser();

        if ($user !== null) {
            $panier = $repo->createQueryBuilder('p');
            $panier->select('p', 'pr')
                   ->join('p.produits', 'pr')
                   ->where('p.user_id = :user')
                   ->andwhere('p.statut = :statut')
                   ->setParameters(['user' => $user, 'statut' => false]);
            $panier = $panier->getQuery()->getOneOrNullResult();

            return $this->render('user/panier.html.twig', [
                'user' => $user,
                'panier' => $panier
            ]);
        }
        else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/panier/{id}/validate", name="validate_panier")
     */
    public function validateCart(Panier $panier, EntityManagerInterface $manager) {
        $panier->setStatut(true);

        $manager->persist($panier);
        $manager->flush();

        $this->addFlash("success", "Nous vous remercions de vos achats, à la prochaine :)");

        return $this->redirectToRoute('eshop');
    }

}
