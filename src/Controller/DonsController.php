<?php

namespace App\Controller;

use App\Form\PostType;
use App\Entity\Dons;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DonsController extends AbstractController
{
    /**
     * @Route("/dons", name="dons")
     */
    public function index(Request $request, ManagerRegistry $mr, UserRepository $userRepository): Response
    {
        $data = $this -> getData();
        $don = new Dons();
        $form = $this->createForm(PostType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $montant = $form-> getData() -> getMontant();
            $this ->addFlash("montant", $montant);
            return $this->redirectToRoute("paiement");
        }

        return $this->render('dons/index.html.twig', [
            'form' => $form ->createView(),
            'montant' => $data
        ]);
    }

    /**
     * @Route("/dons/paiement", name="paiement")
     */
    public function processPaiement(Request $request, ManagerRegistry $mr, UserRepository $userRepository)
    {
        $montant = $this->get('session')->getFlashBag()->get('montant');
        $dataMontant= isset($montant[0]) ? $montant[0] : $montant;

        $form = $this -> createFormBuilder()->add('Nom', TextType::class, array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('NumCarte', NumberType::class, array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('Date', DateType::class, array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('cvv', NumberType::class, array('attr' => array('class' => 'form-control','style' => 'margin-right:5px')))
            ->add('montant', HiddenType::class, ["data" => $dataMontant])
            ->add('Valider', SubmitType::class, array('attr' => array('class' => 'btn btn-success','style' => 'margin-top:5px')))
            ->getForm();
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form ->isValid()){
            $don = new Dons();

            $User= $this->getUser();
            $don -> setMontant(intval($form-> getViewData()["montant"]));
            $don -> setUser($User);
            $don->setDate(new \DateTime());

            $em = $mr->getManager();
            $em->persist($don);
            $em->flush();

            $this->addFlash("success", "Don bien enregistré");
            return $this->redirectToRoute("dons");
        }
        return $this->render('dons/paiement.html.twig', [
            'form' => $form ->createView(),
        ]);


    }

    public function getData()
    {
        $data = $this->getDoctrine()->getRepository(Dons::class)->countMontant();
        return $data[0][1];

    }
}
