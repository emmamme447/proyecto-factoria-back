<?php

namespace App\Controller;

use App\Entity\Area;
use App\Form\AreaType;
use App\Repository\AreaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/area')]
class AreaController extends AbstractController
{
    #[Route('/', name: 'area_index', methods: ['GET'])]
    public function index(AreaRepository $areaRepository): Response
    {
        return $this->render('area/index.html.twig', [
            'areas' => $areaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_area_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AreaRepository $areaRepository): Response
    {
        $area = new Area();
        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $areaRepository->save($area, true);

            return $this->redirectToRoute('app_area_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/new.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'app_area_list', methods: ['GET'])]
    public function listAreas(Request $request, AreaRepository $areaRepository): JsonResponse
    { 

        // Obtenemos todos los datos del repositorio de area
        $listAreas = $areaRepository->findAll(); 

        $data = [];
        
        // Recorre cada uno de los registros del repositorio de area
        foreach ($listAreas as $item) {
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

    #[Route('/{id}', name: 'app_area_show', methods: ['GET'])]
    public function show(Area $area): Response
    {

        return $this->render('area/show.html.twig', [
            'area' => $area,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_area_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Area $area, AreaRepository $areaRepository): Response
    {

        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $areaRepository->save($area, true);

            return $this->redirectToRoute('app_area_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/edit.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_area_delete', methods: ['POST'])]
    public function delete(Request $request, Area $area, AreaRepository $areaRepository): Response
    {

        if ($this->isCsrfTokenValid('delete'.$area->getId(), $request->request->get('_token'))) {
            $areaRepository->remove($area, true);
        }

        return $this->redirectToRoute('app_area_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
