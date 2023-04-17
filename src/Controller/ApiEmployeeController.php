<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\AreaRepository;
use App\Repository\ContractRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PositionRepository;
use App\Repository\RolRepository;
use App\Repository\StatusRepository;
use App\Repository\TeamRepository;
use App\Repository\ManagerRepository;
use App\Repository\PeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/api')]
class ApiEmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_apiemployee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): JsonResponse
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
                'startDate' => $c->getStartDate(),
                'finishDate' => $c->getFinishDate(),
                'manager' => $c->getManager()->getTitle(),
                'period' => $c->getPeriod()->getTitle(),
                'firstPeriod' => $c->getFirstPeriod(),
                'secondPeriod' => $c->getSecondPeriod(),
                'thirdPeriod' => $c->getThirdPeriod(),
                'fourthPeriod' => $c->getFourthPeriod(),
                'fifthPeriod' => $c->getFifthPeriod(),
                'photo' => '/uploads/photos/'.$c->getPhoto(),
                'status' => $c->getStatus()->getTitle(),
            ];
        }
        
        // dump($data);
        // die;

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);

    } 

    #[Route('/employee/{id}/photo', name: 'app_apiemployee_create', methods: ['GET'])]
        public function findPhoto(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, Employee $employee): Response 
        {
        // Devuelve la representación JSON del objeto enviado en la solicitud POST
        // $json = $request->getContent();
        // // Decodifica el JSON en un array asociativo
        // $data = json_decode($json, true);
        // // Acceder a los valores del array utilizando las claves correspondiente
        // $name = $data['name'];
        // $lastName = $data['lastName'];
        // $email = $data['email'];
        // $rol = $data['rol'];
        // $identififying = $data['identififying'];
        // $team = $data['team'];
        // $position = $data['position'];
        // $area = $data['position'];
        // $typeOfContract = $data['typeOfContract'];
        // $startDate = $data['startDate'];
        // $finishDate = $data['finishDate'];
        // $manager = $data['manager'];
        // $photo = $data['photo'];
        // $status = $data['status'];
        
        // // Instancia de la entidad
        // $employee= new Employee();
        // // Seteamos los valores obtenidos de la request
        // $employee->setName($name);
        // $employee->setLastName($lastName);
        // $employee->setEmail($email);
        // $employee->setRoles($rol);
        // $employee->setIdentifying($identififying);
        // $employee->setArea($team);
        // $employee->setPosition($position);
        // $employee->setArea($area);
        // $employee->setTypeOfContract($typeOfContract);
        // $employee->setStartDate($startDate);
        // $employee->setFinishDate($finishDate);
        // $employee->setManager($manager);
        // $employee->setPhoto($photo);
        // $employee->setSchool($status);
        // // Parte de la carga y actualizar los datos en bbdd
        // $entityManager->persist($employee);
        // $entityManager->flush();
        // // Respuesta del servidor
        // $response = $this->json(['message' => 'Mensaje enviado'], 201);
        // Obtener los datos de la imagen
        $photo = $employee->getPhoto();
        // echo "Photo: $photo\n";
        // dump($photo);
        $imagePath = $this->getParameter('kernel.project_dir').'/public/uploads/photo/'.$photo;
        // var_dump($imagePath);
        $imageData = file_get_contents($imagePath);
        // dump($imageData);
        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setContent($imageData);
        // die;
        // return $this->json($response, $status = 200, $headers = ['Content-Type' => 'image/jpeg','Access-Control-Allow-Origin'=>'*']);
        return $response;
        // este codigo nos sirve para mostrarlo en el FRONT
        // useEffect(() => {
        //     fetch(`http://127.0.0.1:8000/api/employee/${id}/photo`)
        //       .then((rese) => rese.blob())
        //       .then((blob) => {
        //         const reader = new FileReader();
        //         reader.readAsDataURL(blob);
        //         reader.onloadend = () => {
        //           setImage(reader.result);
        //         };
        //       });
        //   }, []);
                  // <img style={{ width: "500px" }} src={image} alt="Foto del empleado" />
    }

    #[Route('/create/employee', name: 'app_apiemployeecreate_index', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, PositionRepository $positionRepository, TeamRepository $teamRepository, RolRepository $rolRepository, AreaRepository $areaRepository, ContractRepository $contractRepository, StatusRepository $statusRepository, ManagerRepository $managerRepository, PeriodRepository $periodRepository ): JsonResponse
    {   
        
        // Creamos una instancia a la entidad Employee
        $employee = new Employee();
        // Obtenemos la data enviada en el form-data de la request
        $jsonData = $request->request->get('data');
        // dump("jsonData ", $jsonData);
        
        // Convertir el objeto JSON en un objeto PHP
        $data = json_decode($jsonData);
        // dump("data ", $data);
        // die;
        
        // Obtener la imagen cargada
        $photo = $request->files->get('photo');
        // dump("photo ", $photo);
        // Si se ha cargado una imagen, procesarla
        if ($photo) {
            // Generar un nombre de archivo único para la imagen
            $fileName = md5(uniqid()) . '.' . $photo->guessExtension();

            try {
                // Mover la imagen al directorio de destino
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fileName
                );
                // Guardamos la imagen en la instancia que estamos construyendo para guardarla en bbdd
                $employee->setPhoto($fileName);
            } catch (FileException $e) {
                // Manejar la excepción si falla la carga de la imagen
                return new Response('Error al cargar la imagen', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        // Almacenamos el nombre
        $employee->setName($data->name);
        // Almacenamos el apellido
        $employee->setLastname($data->lastname);
        // Almacenamos el email
        $employee->setEmail($data->email);
        // Almacenamos el rol
        $rol = $rolRepository->find($data->rol);
        $employee->setRol($rol);
        // Almacenamos el identificador
        $employee->setIdentifying($data->identifying);
        // Almacenamos el equipo
        $team = $teamRepository->find($data->team);
        $employee->setTeam($team);
        // Almacenamos el cargo
        $position = $positionRepository->find($data->position);
        $employee->setPosition($position);
        // Almacenamos el area
        $area = $areaRepository->find($data->area);
        $employee->setArea($area);
        // Almacenamos el tipo de contrato
        $contract = $contractRepository->find($data->typeOfContract);
        $employee->setTypeOfContract($contract);
        // Almacenamos la fecha de inicio
        // Convertir la cadena de texto en un objeto DateTime
        $startDate = new \DateTime($data->startDate);
        $employee->setStartDate($startDate);
        // Almacenamos la fecha fin
        // Convertir la cadena de texto en un objeto DateTime
        $finishDate = new \DateTime($data->finishDate);
        $employee->setFinishDate($finishDate);
        // Convertir la cadena de texto en un objeto DateTime
        $firstPeriod = new \DateTime($data->firstPeriod);
        $employee->setFirstPeriod($firstPeriod);
        // Convertir la cadena de texto en un objeto DateTime
        $secondPeriod = new \DateTime($data->secondPeriod);
        $employee->setSecondPeriod($secondPeriod);
        // Convertir la cadena de texto en un objeto DateTime
        $thirdPeriod = new \DateTime($data->thirdPeriod);
        $employee->setThirdPeriod($thirdPeriod);
        // Convertir la cadena de texto en un objeto DateTime
        $fourthPeriod = new \DateTime($data->fourthPeriod);
        $employee->setFourthPeriod($fourthPeriod);
        // Convertir la cadena de texto en un objeto DateTime
        $fifthPeriod = new \DateTime($data->fifthPeriod);
        $employee->setFifthPeriod($fifthPeriod);
        // Almacenamos el manager
        $period = $periodRepository->find($data->period);
        $employee->setPeriod($period);
        // Almacenamos el manager
        $manager = $managerRepository->find($data->manager);
        $employee->setManager($manager);
        // Almacenamos el estado
        $status = $statusRepository->find($data->status);
        $employee->setStatus($status);
        // dump($employee);
        // dump("employee ", $employee);
        // die;

        // Guardar el objeto Employee en la base de datos
        $entityManager->persist($employee);
        $entityManager->flush();
        // Retornamos el id del usuario
        // $id = $employee->getId();
        //creando un objeto que envie los datos
        $result[] = [
            'id' => $employee->getId(),
            'name' => $employee->getName(),
            'lastname' => $employee->getLastName(),
            'email' => $employee->getEmail(),
            'rol' => $employee->getRol()->getTitle(),
            'identifying' => $employee->getIdentifying(),
            'team' => $employee->getTeam()->getTitle(),
            'position' => $employee->getPosition()->getTitle(),
            'area' => $employee->getArea()->getTitle(),
            'typeOfContract' => $employee->getTypeOfContract()->getTitle(),
            'startDate' => $employee->getStartDate(),
            'finishDate' => $employee->getFinishDate(),
            'manager' => $employee->getManager()->getTitle(),
            'period' => $employee->getPeriod()->getTitle(),
            'firstPeriod' => $employee->getFirstPeriod(),
            'secondPeriod' => $employee->getSecondPeriod(),
            'thirdPeriod' => $employee->getThirdPeriod(),
            'fourthPeriod' => $employee->getFourthPeriod(),
            'fifthPeriod' => $employee->getFifthPeriod(),
            'photo' => $employee->getPhoto(),
            'status' => $employee->getStatus()->getTitle(),
        ];

        return $this->json($result, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);

    } 

}