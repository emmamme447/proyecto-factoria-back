<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordchangeuserController extends AbstractController
{
    #[Route('/passwordchangeuser', name: 'passwordchangeuser', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        // Crear formulario para cambiar contraseña
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Verificar que la contraseña actual sea correcta
            if (!$passwordHasher->isPasswordValid($user, $form->get('currentPassword')->getData())) {
                $this->addFlash('error', 'La contraseña actual no es correcta.');

                return $this->redirectToRoute('passwordchangeuser');
            }

            // Actualizar la contraseña del usuario
            $newHashedPassword = $passwordHasher->hashPassword($user, $form->get('newPassword')->getData());
            $user->setPassword($newHashedPassword);
            $entityManager->flush();

            $this->addFlash('success', 'La contraseña se ha cambiado correctamente.');

            return $this->redirectToRoute('home');
        }

        return $this->render('passwordchangeuser/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}