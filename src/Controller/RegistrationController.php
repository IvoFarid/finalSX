<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $authenticator, LoginAuthenticator $formAuthenticator): Response
    {
        // user creation after Register. TODO: Authenticate after register.
        if($request->isMethod('POST')) {
          $user = new User();
          $username = $request->request->get('username');
          $plainPassword = $request->request->get('password');
          $email = $request->request->get('email');
          $user->setImagePath($this->GetParameter('kernel.project_dir') . '/public/salchidog.jpg');
          $user->setUsername($username);
          $user->setEmail($email);
          $user->setPassword(
            $userPasswordHasher->hashPassword(
              $user,
              $plainPassword
            ));
          $user->setFollowers(0);
          $user->setFollowing(0);
          $user->setQtweets(0);
          $datetimeIm = new \DateTimeImmutable('now');
          $datetime = new \Datetime('now');
          $user->setCreatedAt($datetimeIm);
          $user->setUpdatedAt($datetime);
          $entityManager->persist($user);
          $entityManager->flush();

          return $authenticator->authenticateUser(
            $user, 
            $formAuthenticator, 
            $request);
        }
        
        return $this->render('registration/register.html.twig');
    }
}
