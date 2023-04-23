<?php

namespace App\Controller;

use App\Entity\Calendar;
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
            // Buscamos todos los datos de la tabla employee
            $employee = $employeeRepository->findAll();
            // Formateamos la data de la tabla
            $data = $employeeRepository->formatData($employee);
            // Retornamos la data formateada
            return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
        } 

    #[Route('/employee/{id}/photo', name: 'app_apiemployee_create', methods: ['GET'])]
        public function findPhoto(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, Employee $employee): Response 
        {
            // Obtenemos la foto del employee
            $photo = $employee->getPhoto();
            // Buscamos la foto en la carpeta indicada
            $imagePath = $this->getParameter('kernel.project_dir').'/public/uploads/photo/'.$photo;
            // Obtenemos el contenido de la imagen
            $imageData = file_get_contents($imagePath);
            // Creamos una instancia para la respuesta
            $response = new Response();
            // Seteamos las cabeceras para enviar el contenido tipo imagen y el ACAO
            $response->headers->set('Content-Type', 'image/jpeg');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            // Seteamos el contenido en el response y retornamos la imagen
            $response->setContent($imageData);
            return $response;
        }

    #[Route('/employee/new', name: 'app_apiemployeecreate_new', methods: ['POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager, PositionRepository $positionRepository, TeamRepository $teamRepository, RolRepository $rolRepository, AreaRepository $areaRepository, ContractRepository $contractRepository, StatusRepository $statusRepository, ManagerRepository $managerRepository, PeriodRepository $periodRepository ): JsonResponse
        {   
            // Creamos una instancia a la entidad Employee
            $employee = new Employee();
            // Creamos una instancia a la entidad Calendar
            $period1 = new Calendar();
            $period2 = new Calendar();
            $period3 = new Calendar();
            $period4 = new Calendar();
            $period5 = new Calendar();
            // Obtenemos la data enviada en el form-data de la request
            $jsonData = $request->request->get('data');
            // Convertir el objeto JSON en un objeto PHP
            $data = json_decode($jsonData);
            // Obtener la imagen cargada
            $photo = $request->files->get('photo');
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

            // Añadiremos los eventos que se hayan insertado en el formulario del empleado
            $events = [];
            if ($data->firstPeriod) {
                // Convertir la cadena de texto en un objeto DateTime
                $date = new \DateTime($data->firstPeriod);
                // Actualizamos el campo primer periodo de la variable employee que hace referencia a la entidad employee
                $employee->setFirstPeriod($date);
                // Actualizamos el campo title con el texto Primer Seguimiento
                $period1->setTitle('Primer Seguimiento');
                // Actualizamos el campo start date
                $period1->setStartDate($date);
                // Actualizamos el campo finish date
                $period1->setFinishDate($date);
                // Actualizamos el campo recipient con el dato email del empleado
                $period1->setRecipient($data->email);
                $events[] = $period1;
            }
            if ($data->secondPeriod) {
                // Convertir la cadena de texto en un objeto DateTime
                $secondPeriod = new \DateTime($data->secondPeriod);
                // Actualizamos el campo segundo periodo de la variable employee que hace referencia a la entidad employee
                $employee->setSecondPeriod($secondPeriod);
                // Actualizamos el campo title con el texto Segundo Seguimiento
                $period2->setTitle('Segundo Seguimiento');
                // Actualizamos el campo start date
                $period2->setStartDate($secondPeriod);
                // Actualizamos el campo finish date
                $period2->setFinishDate($secondPeriod);
                // Actualizamos el campo recipient con el dato email del empleado
                $period2->setRecipient($data->email);
                $events[] = $period2;
            }
            if ($data->thirdPeriod) {
                // Convertir la cadena de texto en un objeto DateTime
                $thirdPeriod = new \DateTime($data->thirdPeriod);
                // Actualizamos el campo tercer periodo de la variable employee que hace referencia a la entidad employee
                $employee->setThirdPeriod($thirdPeriod);
                // Actualizamos el campo title con el texto Tercer Seguimiento
                $period3->setTitle('Tercer Seguimiento');
                // Actualizamos el campo start date
                $period3->setStartDate($thirdPeriod);
                // Actualizamos el campo finish date
                $period3->setFinishDate($thirdPeriod);
                // Actualizamos el campo recipient con el dato email del empleado
                $period3->setRecipient($data->email);
                $events[] = $period3;
            }
            if ($data->fourthPeriod) {
                // Convertir la cadena de texto en un objeto DateTime
                $fourthPeriod = new \DateTime($data->fourthPeriod);
                // Actualizamos el campo cuarto periodo de la variable employee que hace referencia a la entidad employee
                $employee->setFourthPeriod($fourthPeriod);
                // Actualizamos el campo title con el texto Cuarto Seguimiento
                $period4->setTitle('Cuarto Seguimiento');
                // Actualizamos el campo start date
                $period4->setStartDate($fourthPeriod);
                // Actualizamos el campo finish date
                $period4->setFinishDate($fourthPeriod);
                // Actualizamos el campo recipient con el dato email del empleado
                $period4->setRecipient($data->email);
                $events[] = $period4;
            }
            if ($data->fifthPeriod) {
                // Convertir la cadena de texto en un objeto DateTime
                $fifthPeriod = new \DateTime($data->fifthPeriod);
                // Actualizamos el campo quinto periodo de la variable employee que hace referencia a la entidad employee
                $employee->setFifthPeriod($fifthPeriod);
                // Actualizamos el campo title con el texto Quinto Seguimiento
                $period5->setTitle('Quinto Seguimiento');
                // Actualizamos el campo start date
                $period5->setStartDate($fifthPeriod);
                // Actualizamos el campo finish date
                $period5->setFinishDate($fifthPeriod);
                // Actualizamos el campo recipient con el dato email del empleado
                $period5->setRecipient($data->email);
                $events[] = $period5;
            }
            // Almacenamos el manager
            $period = $periodRepository->find($data->period);
            $employee->setPeriod($period);
            // Almacenamos el manager
            $manager = $managerRepository->find($data->manager);
            $employee->setManager($manager);
            // Almacenamos el estado
            $status = $statusRepository->find($data->status);
            $employee->setStatus($status);

            foreach ($events as $evnt) {
                $entityManager->persist($evnt);
            }
            // Guardar el objeto Employee en la base de datos
            $entityManager->persist($employee);
            $entityManager->flush();

            //creando un array de objeto para almacenar y retornar el employee creado
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
    
    #[Route('/employee/filter/status/{status}', name: 'app_apiemployeefilterstatus_filterstatus', methods: ['GET'])]
        public function filterstatus(EmployeeRepository $employeeRepository, $status)
        {
            // Filtramos los empleados por el status enviado en el parametro de la ruta
            $resultQuery = $employeeRepository->filterEmployeeByStatus($status);
            // Formateamos la data
            $data = $employeeRepository->formatData($resultQuery);
            // Retornamos la data
            return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }

    #[Route('/employee/filter/manager/{identifying}', name: 'app_apiemployeefiltermanager_filtermanager', methods: ['GET'])]
        public function filtermanager(EmployeeRepository $employeeRepository, $identifying)
        {
            // Filtramos los empleados por el identificador del manager enviado en el parametro de la ruta
            $resultQuery = $employeeRepository->filterEmployeeByManager($identifying);
            // Formateamos la data
            $data = $employeeRepository->formatData($resultQuery);
            // Retornamos la data
            return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }

    #[Route('/employee/filter/email/{email}', name: 'app_apiemployeefilteremail_filteremail', methods: ['GET'])]
        public function filteremail(EmployeeRepository $employeeRepository, $email)
        {
            // Filtramos los empleados por el email enviado en el parametro de la ruta
            $resultQuery = $employeeRepository->filterEmployeeByEmail($email);
            // Formateamos la data
            $data = $employeeRepository->formatData($resultQuery);
            // Retornamos la data
            return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }
    }
