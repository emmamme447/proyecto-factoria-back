<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

#[Route('/api')]
class ApiEmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_apiemployee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {   
        $employee = $employeeRepository->findAll();

        $data = [];

        foreach ($employee as $c) {
            // dump($c->getTypeOfContract());
            // dump($c->getTypeOfContract()->getId());
            $data[] = [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'lastname' => $c->getLastName(),
                'email' => $c->getEmail(),
                'rol' => $c->getRol()->getTitle(),
                'identifying' => $c->getIdentifying(),
                'team' => $c->getTeam()->getTitle(),
                'position' => $c->getPosition()->getTitle(),
                'area' => $c->getArea()->getTitle(),
                'typeOfContract' => $c->getTypeOfContract()->getTitle(),
                'startDate' => $c->getStartDate()->date,
                'finishDate' => $c->getFinishDate(),
                'manager' => $c->getManager(),
                'photo' => $c->getPhoto(), 
                'status' => $c->getStatus()->getTitle(),
            ];
        }
        // dump($data);
        // die;

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);

    } 

    #[Route('/listemployee', name: 'app_apiemployee_create', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): JsonResponse
        {
        // Devuelve la representación JSON del objeto enviado en la solicitud POST
        $json = $request->getContent();
        // dump($json);
        // die;
        // Decodifica el JSON en un array asociativo
        $data = json_decode($json, true);
        // Acceder a los valores del array utilizando las claves correspondiente
        $name = $data['name'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $rol = $data['rol'];
        $identififying = $data['identififying'];
        $team = $data['team'];
        $position = $data['position'];
        $area = $data['position'];
        $typeOfContract = $data['typeOfContract'];
        $startDate = $data['startDate'];
        $finishDate = $data['finishDate'];
        $manager = $data['manager'];
        $photo = $data['photo'];
        $status = $data['status'];
        
        // Instancia de la entidad
        $employee= new Employee();
        // Seteamos los valores obtenidos de la request
        $employee->setName($name);
        $employee->setLastName($lastName);
        $employee->setEmail($email);
        $employee->setRoles($rol);
        $employee->setIdentifying($identififying);
        $employee->setArea($team);
        $employee->setPosition($position);
        $employee->setArea($area);
        $employee->setTypeOfContract($typeOfContract);
        $employee->setStartDate($startDate);
        $employee->setFinishDate($finishDate);
        $employee->setManager($manager);
        $employee->setPhoto($photo);
        $employee->setSchool($status);
        // Parte de la carga y actualizar los datos en bbdd
        $entityManager->persist($employee);
        $entityManager->flush();
        // Respuesta del servidor
        $response = $this->json(['message' => 'Mensaje enviado'], 201);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

}