<?php

namespace App\Controller;

use App\Entity\Place;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{

    /**
     * @Route("/listePlace", name="liste_Place")
     */
    public function afficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Places = $em->getRepository('App:Place')
            ->findAll();
        return $this->render('place/listePlace.html.twig', array(
            'place' => $Places
        ));
    }

    /**
     * @Route("/generateCsv", name="generateCsv")
     */
    public function generateCsv(){
        $em = $this->getDoctrine()->getManager();
        $Places = $em->getRepository('App:Place')
            ->findAll();
        foreach ($Places as $place) {
            $ligne=array($place->getName(),$place->getAddress(),$place->getCity(),$place->getZipCode());
            $file = fopen("C:\Users\Ordinateur HP\Desktop\place.csv","a+");
            fputcsv($file,$ligne);
            fclose($file);

        }
        return $this->render('place/listePlace.html.twig', array(
            'place' => $Places
        ));


    }
    /**
     * @Route("/ajouterPlace",name="ajouter_place")
     */
    public function ajouter( Request $request,ObjectManager $manager){
        $Places = new Place();
        $form =$this->createFormBuilder($Places)
            ->add('name')
            ->add('address')
            ->add('zipCode')
            ->add('City')
            ->add('save',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() &&$form->isValid()){
            $manager->persist($Places);
            $manager->flush();
            return $this->redirectToRoute('liste_Place',[
                "place"=>$Places
            ]);
        }else {

            return $this->render('place/ajouterPlace.html.twig', [
                'form' => $form->createView()

            ]);
        }
    }
}
