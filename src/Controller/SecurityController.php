<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/Authentification", name="page_authentification")
     */
    public function registration(Request $request, ObjectManager $manager /* UserPasswordEncoderInterface $encoder*/)
    {
        $User = new  User();
        /*  $form = $this->createForm(RegistrationType::class ,$User );*/
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder($User)->add('userName')
            ->add('passWord', PasswordType::class)
            ->add('ConfirmePassWord', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*crypter le mot de passe  avec $hash
           $hash =$encoder->encodePassword($User, $User->getPassWord());
           $User->setPassWord($hash);*/
            $user = $manager->getRepository("App:User")->findOneBy(['userName' => $form->get('userName')->getData()]);

            if ($user === null) {
                return $this->redirectToRoute('page_authentification');
            }

            $userName = $user->getUserName();
            $passWord = $user->getPassWord();

            if ($form->get('passWord')->getData() === $passWord) {
                return $this->redirectToRoute('liste_appointment');
            } else {
                return $this->render('security/authentification.html.twig', [
                    'form' => $form->createView(),
                    'message' => $this->addFlash('error', 'Erreur d\'authentification')

                ]);
            }


            //     $manager->persist($User);
            //  $manager->flush();

            //   return $this->redirectToRoute('liste_appointment');
            //   }
            //  return $this->render('security/authentification.html.twig', [
            // 'form' => $form->createView()

            //   ]);
            // }

            /**
             * @Route("/connexion", name="formulaire_login")
             */

            //   public function  login(){
            //  return $this->render( 'security\login.html.twig');
            //  }
        }


        return $this->render('security/authentification.html.twig', [
            'form' => $form->createView()

        ]);


    }
}
