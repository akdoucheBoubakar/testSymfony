<?php

namespace App\Controller;

use App\Entity\Appointment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentController extends AbstractController
{



    /**
     * @Route("/listeAppointment", name="liste_appointment")
     */
    public function afficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Appointment = $em->getRepository('App:Appointment')
            ->findAll();
        return $this->render('appointment/listeAppointment.html.twig', array(
            'Appointments' => $Appointment
        ));
    }
    /**
     * @Route("/ajouterAppointment",name="ajouter_appointment")
     */
    public function ajouterAppointment( Request $request,ObjectManager $manager){
        $Appointment = new Appointment();
        $form =$this->createFormBuilder($Appointment)
            ->add('idUser')
            ->add('idCustomer')
            ->add('idPlace')
            ->add('date',DateType::class)

            ->add('save',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() &&$form->isValid()){
            $manager->persist($Appointment);
            $manager->flush();
            return $this->redirectToRoute('liste_appointment',[
                "Appointments"=>$Appointment
            ]);
        }else {

            return $this->render('appointment/ajouterAppointment.html.twig', [
                'form' => $form->createView()

            ]);
        }
    }
  // fonction qui   fait la modification sur  les appointement 
    /**
     * @Route("/modifierAppointment/{id}" , name="modifier_appointment")
     */
    public function modifierAction(Request $request, ObjectManager $manager,$id)
    {

        $em =$this->getDoctrine()->getManager();
        $Appointment= $em->getRepository(Appointment::class)
            ->find($id);
        $form =$this->createFormBuilder($Appointment)
            ->add('idUser')
            ->add('idCustomer')
            ->add('idPlace')
            ->add('date',DateType::class)

            ->add('save',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($Appointment);
            $manager->persist($Appointment);
            $manager->flush();


            return $this->redirectToRoute('liste_appointment',[
                "Appointments"=>$Appointment
            ]);
        } else {

            return $this->render('appointment/ajouterAppointment.html.twig', array(
                'form' => $form->createView()

            ));
        }
    }
    /**
     * @Route("/supprimerAppointment/{id}" , name="supprimer_appointment")
     */
    public function supprimerAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Appointment = $em->getRepository('App:Appointment')
            ->find($id);
        $em->remove($Appointment);
        $em->flush();

        return $this->redirectToRoute('liste_appointment', array(

        ));
    }
}
