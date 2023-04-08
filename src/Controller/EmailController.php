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
use Doctrine\ORM\EntityManagerInterface;


class EmailController extends AbstractController
{
    #[Route('/email', name: 'email')]

    public function generatePassword($length = 12)
    {
    $bytes = random_bytes($length / 2);
    $password = substr(bin2hex($bytes), 0, $length);
    return $this->render('email/email.html.twig', ['password' => $password]);
    }

    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(EmailtoType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $transport = Transport::fromDsn('smtp://emmarentero@gmail.com:tsqqgksxiyoiyijx@smtp.gmail.com:587');

            $mailer = new Mailer($transport);

            list($password) = $this->generatePassword();

            $email = (new Email())

            ->from('emmarentero@gmail.com')

            ->to('emmarentero@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Email de bienvenida a FactoríaF5')
            ->text('<h1>¡¡¡¡Bienvenida compañer@!!!!</h1>

            <H5>TU CONTRASEÑA ES:{{ password }} </H5>
            
            <h4>Por favor, procede a modificar tu contraseña accediendo al enlace que te indicamos a continuación:</h4>
            
            ');

        $mailer->send($email);

        }

        return $this->renderForm('email/index.html.twig', [
            'form' => $form,
        ]);
        
    }

    public function HashPassword($password, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $entityManager->persist();
    $entityManager->flush();
    return $hashedPassword;
    }

}
        
