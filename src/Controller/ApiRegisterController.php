<?php


namespace App\Controller;


use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;


class ApiRegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods:'POST')]
    public function Registration(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();//esta es la entidad a la que hay q referenciar
        $user->setEmail($data['username']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        //$user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
