<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\EmailtoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;


class EmailController extends AbstractController
{
    #[Route('/email', name: 'email')]

    /**
    * @param string $username
    * @param string $email
    */
    public function getAutoPass($username, $email)
    {
        $autopass = strtolower(chr(64 + rand(1, 26)) . strtolower($username[2] . $email[1] . rand(1, 99) . $username[1] . $email[0]));
        return $autopass;
    }

    /**
    * @Route("/email", name="email")
    * @param Request $request
    * @param MailerInterface $mailer
    * @param UserPasswordHasherInterface $userPasswordHasher
    * @param ManagerRegistry $managerRegistry
    */
    public function index(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry): Response
    {
        
        $form = $this->createForm(EmailtoType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $username = $data->getUsername();
            $email = $data->getEmail();
            
            $password = $this->getAutoPass($username, $email);

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            
            $transport = Transport::fromDsn('smtp://emmarentero@gmail.com:tsqqgksxiyoiyijx@smtp.gmail.com:587');

            $mailer = new Mailer($transport);

            $email = (new Email())

            ->from('emmarentero@gmail.com')

            ->to('emmarentero@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Email de bienvenida a FactoríaF5')
            ->text('

            <h1>¡¡¡¡Bienvenida compañer@!!!!</h1>

            <H5>TU CONTRASEÑA ES:{{ $password }} </H5>
            
            <h4>Por favor, procede a modificar tu contraseña accediendo al enlace que te indicamos a continuación:</h4>
            
            ')
            ->html('style="color: #020100; background-color: #ffa37f; width: 100%; padding: 16px 0; text-align: center');

        $mailer->send($email);

        }

        return $this->renderForm('email/index.html.twig', [
            'form' => $form,
        ]); 

    }

}

    


        
