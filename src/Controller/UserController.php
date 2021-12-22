<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('user/commandes.html.twig', [
            'user' => $user
        ]);
    }
}
