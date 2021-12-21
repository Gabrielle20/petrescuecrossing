<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     */
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }
    /**
     * @Route("/email/send", name="emailSending")
     */
    public function sendMail(MailerInterface  $mailer)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('petrecuecrossing@gmail.com', 'Team PetRescueCrossing'))
            ->to('webflopgt@gmail.com')
            ->subject('Demande d\'Adoption')
            ->priority(Email::PRIORITY_HIGH)
            ->htmlTemplate('email/adoptRequest.html.twig')
            ->context([
                'animalName' => 'Ragnar',
                'username' =>'Pagot D.FloW'
            ]);
        $mailer->send($email);
        if($mailer){
            return $this->render('email/send.html.twig', [
                'etat' => 'success',
            ]);
        }
        else{
            return $this->render('email/send.html.twig', [
                'etat' => 'fail',
            ]);
        }
    }
    
}
