<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
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
        throw new \Exception (message: 'UPs! ha courrido un error, sorry');
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
    public function edit(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
