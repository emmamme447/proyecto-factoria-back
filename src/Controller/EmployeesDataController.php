<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesDataController extends AbstractController
{
    #[Route('/dashboard/employees/data', name: 'employees_data', methods: ['GET'])]
    public function employeesData(ManagerRegistry $managerRegistry): Response
    {
        $user = $managerRegistry->getRepository(User::class)->findAll();

        foreach ($user as $users) {
            $data[] = [
                'id' => $users->getId(),
                'username' => $users->getUsername(),
            ];
        }

        $datanueva = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $response = new Response($datanueva);

        $response->headers->add([
            'Content-Type' => 'application/json'
        ]);

        return $response;
    }
}
