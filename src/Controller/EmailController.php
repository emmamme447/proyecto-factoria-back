<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\EmailtoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


class EmailController extends AbstractController
{
    #[Route('/email', name: 'email')]

    public function index(Request $request, MailerInterface $mailer): Response
    {
        
        $form = $this->createForm(EmailtoType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $transport = Transport::fromDsn('smtp://emmarentero@gmail.com:gdmjziwrhmmsrbkd@smtp.gmail.com:587');

            $mailer = new Mailer($transport);


            $email = (new Email())

            ->from('emmarentero@gmail.com')

            ->to('emmarentero@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Autoevaluación FactoríaF5')

            ->text('¡Hola Bienvenid@!

            Estamos muy content@s que seas parte del equipo de Factoría F5, contigo seguimos creciendo y creando oportunidades a nuestr@s Coders.
                
            Verás que te adjuntamos un link con una autoevaluación. Para nosotr@s es muy importante tener tu feedback durante tu periodo de prueba. Confiamos que para llegar lejos debemos ser capaces de ser críticos con nosotros mismos y tener espacios para seguir creciendo y aprendiendo.

            En caso de cualquier duda, estamos a tu disposición.

            FACTORIA F5

            ')

            ->html('
            
            <div style="color: #020100; background-color: #FFA37F; width: 100%; padding: 16px 0; text-align: center; color-padding: #FD3903">
  
                <h1>¡Hola Bienvenid@!</h1>
  
                <h4>Estamos muy content@s que seas parte del equipo de Factoría F5, contigo seguimos creciendo y creando oportunidades a nuestr@s Coders.</h4>

                <h4>Verás que te adjuntamos un link con una autoevaluación. Para nosotr@s es muy importante tener tu feedback durante tu periodo de prueba. Confiamos que para llegar lejos debemos ser capaces de ser críticos con nosotros mismos y tener espacios para seguir creciendo y aprendiendo.</h4>

                    <a href="https://forms.gle/xuiYspLRaWWTxcWi9">Enlace al formulario de autoevaluación</a>
  
                <h4>En caso de cualquier duda, estamos a tu disposición.</h4>
  
                <h2>People & Culture</H2>
  
        ');

        $mailer->send($email);

        $this->addFlash('success', 'El correo electrónico se ha enviado correctamente.');

        }

        return $this->renderForm('email/index.html.twig', [
            'form' => $form,
        ]); 

    }

}

    


        
