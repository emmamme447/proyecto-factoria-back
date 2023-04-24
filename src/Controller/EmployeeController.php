<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\AreaRepository;
use App\Repository\ContractRepository;
use App\Repository\PositionRepository;
use App\Repository\RolRepository;
use App\Repository\StatusRepository;
use App\Repository\TeamRepository;
use App\Repository\ManagerRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PeriodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/employee')]
class EmployeeController extends AbstractController
{
  #[Route('/', name: 'app_employee_index', methods: ['GET'])]
  public function index(EmployeeRepository $employeeRepository): Response
  {
    return $this->render('employee/index.html.twig', [
      'employees' => $employeeRepository->findAll(),
      ]);
  }

  #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
  public function new(Request $request, SluggerInterface $slugger,EmployeeRepository $employeeRepository): Response
  {
    
    $employee = new Employee();
    $form = $this->createForm(EmployeeType::class, $employee);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFile $foto */
      $photo = $form->get('photo')->getData();
      // dump($form);
      // die;
      // dump($photo)
    
      // this condition is needed because the 'brochure' field is not required
      // so the PDF file must be processed only when a file is uploaded

      if ($photo) {
        $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
          // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
                  
        $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
        $photo->move(
        $this->getParameter('photo_directory'),
        $newFilename
          );
        } catch (FileException $e) {
        throw new \Exception (message: 'UPs! ha ocurrido un error, sorry');
        // ... handle exception if something happens during file upload
        }


      // updates the 'brochureFilename' property to store the PDF file name
      // instead of its contents
        $employee->setPhoto($newFilename);
          // dump($usuario);
        // die;
      }    

      $employeeRepository->save($employee, true);
		  // ... persist the $product variable or any other work

      return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('employee/new.html.twig', [
      'employee' => $employee,
      'form' => $form,
    ]);
  }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SluggerInterface $slugger, Employee $employee, EmployeeRepository $employeeRepository, PositionRepository $positionRepository, TeamRepository $teamRepository, RolRepository $rolRepository, AreaRepository $areaRepository, ContractRepository $contractRepository, StatusRepository $statusRepository, ManagerRepository $managerRepository, PeriodRepository $periodRepository): Response
    { 
      $form = $this->createForm(EmployeeType::class, $employee);
      // dump($form);
      // dump($request->files->get('employee')['photo']);
      // dump($request->isMethod('POST'));
      // die;
      
      if ($request->isMethod('POST') && $request->files->get('employee')['photo']){
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           /** @var UploadedFile $foto */
          $photo = $form->get('photo')->getData();
          // dump($photo);
          // die;
          
          // this condition is needed because the 'brochure' field is not required
          // so the PDF file must be processed only when a file is uploaded
      
          if ($photo) {
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
              // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
                          
            $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
        
            // Move the file to the directory where brochures are stored
            try {
            $photo->move(
            $this->getParameter('photo_directory'),
            $newFilename
              );
            } catch (FileException $e) {
            throw new \Exception (message: 'UPs! ha ocurrido un error, sorry');
            // ... handle exception if something happens during file upload
            }
        
        
          // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $employee->setPhoto($newFilename);
              // dump($usuario);
              // die;
          }    
      
        $employeeRepository->save($employee, true);
        // ... persist the $product variable or any other work
    
    
        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }
      } else if ($request->isMethod('POST')) {
        // dump($request->files->has('photo'));
        $data = $request->request->get('employee');
        // dump($data);
        // dump($data['lastname']);
        

        // Almacenamos el nombre
        $employee->setName($data["name"]);
        // Almacenamos el apellido
        $employee->setLastname($data['lastname']);
        // Almacenamos el email
        $employee->setEmail($data['email']);
        // Almacenamos el rol
        $rol = $rolRepository->find($data['rol']);
        $employee->setRol($rol);
        // Almacenamos el identificador
        $employee->setIdentifying($data['identifying']);
        // Almacenamos el equipo
        $team = $teamRepository->find($data['team']);
        $employee->setTeam($team);
        // Almacenamos el cargo
        $position = $positionRepository->find($data['position']);
        $employee->setPosition($position);
        // Almacenamos el area
        $period = $periodRepository->find($data['period']);
        $employee->setPeriod($period);
        // Almacenamos el area
        $area = $areaRepository->find($data['area']);
        $employee->setArea($area);
        // Almacenamos el tipo de contrato
        $contract = $contractRepository->find($data['typeOfContract']);
        $employee->setTypeOfContract($contract);
        // Almacenamos la fecha de inicio
        // Convertir la cadena de texto en un objeto DateTime
        $message = sprintf('%s-%s-%s', $data['startDate']['year'], $data['startDate']['month'], $data['startDate']['day']);
        $startDate = new \DateTime($message);
        $employee->setStartDate($startDate);
        // Almacenamos la fecha fin
        // Convertir la cadena de texto en un objeto DateTime
        $message = sprintf('%s-%s-%s', $data['finishDate']['year'], $data['finishDate']['month'], $data['finishDate']['day']);
        $finishDate = new \DateTime($message);
        $employee->setFinishDate($finishDate);
        // Almacenamos el manager
        $manager = $managerRepository->find($data['manager']);
        $employee->setManager($manager);
          // Convertir la cadena de texto en un objeto FirstTime
        if ($data['firstPeriod']['year'] && $data['firstPeriod']['month'] && $data['firstPeriod']['day']){
          $message = sprintf('%s-%s-%s', $data['firstPeriod']['year'], $data['firstPeriod']['month'], $data['firstPeriod']['day']);
          $firstPeriod = new \DateTime($message);
          $employee->setFirstPeriod($firstPeriod);
        }
      
        // Convertir la cadena de texto en un objeto SecondTime
        if ($data['secondPeriod']['year'] && $data['secondPeriod']['month'] && $data['secondPeriod']['day']) {
          $message = sprintf('%s-%s-%s', $data['secondPeriod']['year'], $data['secondPeriod']['month'], $data['secondPeriod']['day']);
          $secondPeriod = new \DateTime($message);
          $employee->setSecondDate($secondPeriod);
        }
        // Convertir la cadena de texto en un objeto ThirdTime
        if ($data['thirdPeriod']['year'] && $data['thirdPeriod']['month'] && $data['thirdPeriod']['day']){
          $message = sprintf('%s-%s-%s', $data['thirdPeriod']['year'], $data['thirdPeriod']['month'], $data['thirdPeriod']['day']);
          $thirdPeriod = new \DateTime($message);
          $employee->setThirdPeriod($thirdPeriod);
        }
        // Convertir la cadena de texto en un objeto FourthTime
        if ($data['fourthPeriod']['year'] && $data['fourthPeriod']['month'] && $data['fourthPeriod']['day']){
          $message = sprintf('%s-%s-%s', $data['fourthPeriod']['year'], $data['fourthPeriod']['month'], $data['fourthPeriod']['day']);
          $fourthPeriod = new \DateTime($message);
          $employee->setFourthPeriod($fourthPeriod);
        }
        // Convertir la cadena de texto en un objeto FifTime
        if ($data['fifthPeriod']['year'] && $data['fifthPeriod']['month'] && $data['fifthPeriod']['day']){
          $message = sprintf('%s-%s-%s', $data['fifthPeriod']['year'], $data['fifthPeriod']['month'], $data['fifthPeriod']['day']);
          $fifthPeriod = new \DateTime($message);
          $employee->setFifthDate($fifthPeriod);
        }
        // Almacenamos el estado
        $status = $statusRepository->find($data['status']);
        $employee->setStatus($status);
        // die;
          $employeeRepository->save($employee, true);
          return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
      }

      return $this->renderForm('employee/edit.html.twig', [
        'employee' => $employee,
        'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            $employeeRepository->remove($employee, true);
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
