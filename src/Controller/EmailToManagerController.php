<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\EmailToManagerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class EmailToManagerController extends AbstractController
{
    #[Route('/email/to/manager', name: 'email_to_manager')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        
        $form = $this->createForm(EmailToManagerType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $transport = Transport::fromDsn('smtp://emmarentero@gmail.com:tsqqgksxiyoiyijx@smtp.gmail.com:587');

            $mailer = new Mailer($transport);


            $email = (new Email())

            ->from('emmarentero@gmail.com')

            ->to('emmarentero@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Evaluación final de empleado en periodo de prueba')

            ->text('Hola, ¿cómo estás, compañer@?

            Te remito el link para que procedas a completar la evaluación final:
                
            Por favor, recuerda que en el formulario al que te lleva este link solo debes rellenar las partes: Información general del evaluado y el evaluador, Valoración de los valores F5, Valoración competencias transversales, Valoración cualitativa y Despedida y agradecimiento.que son las correspondientes a tu autoevaluación.

            Cualquier duda, por favor, consulta de nuevo con RRHH.

            Un saludo

            FACTORIA F5

            ')

            ->html('
            
            <div style="color: #020100; background-color: #FFA37F; width: 100%; padding: 16px 0; text-align: center; color-padding: #FD3903">
  
                <h1>Hola, ¿cómo estás, compañer@?</h1>
  
                <h4>Te remito el link para que procedas a completar la evaluación final:</h4>
  
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLScOhrA7xLvpODBWUUEx5_A1-B079SDHxNSX9hqDMdjzTGyknQ/viewform">Enlace al formulario de autoevaluación</a>
                  
                <h2>Por favor, recuerda que en el formulario al que te lleva este link solo debes rellenar las partes: Información general del evaluado y el evaluador, Valoración de los valores F5, Valoración competencias transversales, Valoración cualitativa y Despedida y agradecimiento, que son las correspondientes a tu autoevaluación.</h2>
  
                <h4>Cualquier duda, por favor, consulta de nuevo con RRHH.</h4>
  
                <h4>Un saludo</H4>
  
                <h2> FACTORIA F5</H2>
  
                    <img src"../../public/assets/image_1.png">
            ');

        $mailer->send($email);

        $this->addFlash('success', 'El correo electrónico se ha enviado correctamente.');

        }

        return $this->renderForm('email_to_manager/index.html.twig', [
            'form' => $form,
        ]); 

    }

}
