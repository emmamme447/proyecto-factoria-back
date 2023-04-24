<?php

namespace App\Controller;

use App\Entity\Rol;
use App\Form\RolType;
use App\Repository\RolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rol')]
class RolController extends AbstractController
{
    #[Route('/', name: 'app_rol_index', methods: ['GET'])]
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('rol/index.html.twig', [
            'rols' => $rolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RolRepository $rolRepository): Response
    {
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolRepository->save($rol, true);

            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rol/new.html.twig', [
            'rol' => $rol,
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'app_rol_list', methods: ['GET'])]
    public function listRol(Request $request, RolRepository $rolRepository): JsonResponse
    { 
        // Obtenemos todos los datos del repositorio de area
        $listRol = $rolRepository->findAll(); 

        $data = [];
        
        // Recorre cada uno de los registros del repositorio de area
        foreach ($listRol as $item) {
            // Guardamos los campos de cada registro en un array
            $data[] = [

                'id' => $item->getId(),
                'title' => $item->getTitle(),
            ];
        }
        // Retornamos una respuesta tipo JSON donde enviamos la data construida
        // status 200 para indicar que todo esta correcto
        // headers Access-Control-Allow-Origin para permitir que cualquier sitio acceda al recurso e interaccione entre diferentes sitios web
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
    

    #[Route('/{id}', name: 'app_rol_show', methods: ['GET'])]
    public function show(Rol $rol): Response
    {
        return $this->render('rol/show.html.twig', [
            'rol' => $rol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rol $rol, RolRepository $rolRepository): Response
    {
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolRepository->save($rol, true);

            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rol/edit.html.twig', [
            'rol' => $rol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rol_delete', methods: ['POST'])]
    public function delete(Request $request, Rol $rol, RolRepository $rolRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $rolRepository->remove($rol, true);
        }

        return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
    }
}
