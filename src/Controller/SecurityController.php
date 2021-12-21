<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setAdmin(0);

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);


            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été créé");

            return $this->redirectToRoute('connexion');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="security_connexion")
     *
     * @return void
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }


    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logout() {
        
    }
}
