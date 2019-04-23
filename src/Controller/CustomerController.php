<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;




use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use App\Form\CustomerType;

class CustomerController extends AbstractController
{
    /**
     * @Route("/liste", name="liste_customer")
     */
    public function afficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Customer = $em->getRepository('App:Customer')
            ->findAll();
        return $this->render('Customer/listeCustomer.html.twig', array(
            'Customers' => $Customer
        ));
    }

    /**
     * @Route("/inscription",name="ajouter_customer")
     */
    public function inscription( Request $request,ObjectManager $manager){
            $Customer = new Customer();
            $form =$this->createFormBuilder($Customer)
            ->add('firstName')
            ->add('lastName')
            ->add('birthDate',DateType::class)
                ->add('save',SubmitType::class)
            ->getForm();

            $form->handleRequest($request);
            if($form->isSubmitted() &&$form->isValid()){
                $manager->persist($Customer);
                $manager->flush();
                return $this->redirectToRoute('liste_customer',[
                    "Customers"=>$Customer
                ]);
        }else {

            return $this->render('Customer/inscription.html.twig', [
                'form' => $form->createView()

            ]);
        }
    }
    /**
     * @Route("/modifier/{id}" , name="modifier_customer")
     */
    public function modifierAction(Request $request, ObjectManager $manager,$id)
    {

        $em =$this->getDoctrine()->getManager();
        $Customer= $em->getRepository(Customer::class)
            ->find($id);
        $form =$this->createFormBuilder($Customer)
            ->add('firstName')
            ->add('lastName')
            ->add('birthDate')
            ->add('save', SubmitType::class)

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($Customer);
            $manager->persist($Customer);
            $manager->flush();


            return $this->redirectToRoute('liste_customer', [
                'Customers' => $Customer
            ]);
        } else {

            return $this->render('Customer/modiffierCustomer.html.twig', array(
                'form' => $form->createView()

            ));
        }
    }
    /**
     * @Route("/supprimer/{id}" , name="supprimer_customer")
     */
    public function supprimerAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Customer = $em->getRepository('App:Customer')
            ->find($id);
        $em->remove($Customer);
        $em->flush();

        return $this->redirectToRoute('liste_customer', array(
            // ...
        ));
    }
}
