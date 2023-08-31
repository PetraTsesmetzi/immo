<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class UserController extends AbstractController
{

    #[Route('/',name: 'app_landing')]
    public function landingpage(): Response
    {
        return $this->render('immo/landing.html.twig');
    }
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/afterlogin', name: 'app_afterlogin')]
    public function afterlogin(): Response
    {
        return $this->redirectToRoute('app_landing');
    }




    #[Route('create/user', name: 'app_user_create')]
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher,
                               UserAuthenticatorInterface $userAuthenticator, FormLoginAuthenticator $formLoginAuthenticator): Response
    {
        $neuerUser=new User();
        $form=$this->createForm(UserFormType::class,$neuerUser);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $neuerUser=$form->getData();
            $neuerUser->setPassword($passwordHasher->hashPassword($neuerUser, $form->get('plainPassword')->getData()));

            $entityManager->persist($neuerUser); //persist erzählt der doctrine kümmer dich mal drum
            $entityManager->flush(); //doctrine schaut nach allen Objekten die persist hinzugefügt hat und  fügt das Objekt in die Tabelle hinzu

            //hier authentifziere ich mich mit dem user
            $userAuthenticator->authenticateUser($neuerUser,$formLoginAuthenticator,$request);

//            return $this->redirectToRoute('app_afterRegister');
        }
        return $this->render('user/user_create.html.twig',['form'=>$form->createView()]);

    }


}
