<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Form\ComentariosType;
use App\Repository\ComentariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comentarios')]
class ComentariosController extends AbstractController
{
    #[Route('/', name: 'app_comentarios_index', methods: ['GET'])]
    public function index(ComentariosRepository $comentariosRepository): Response
    {
        return $this->render('comentarios/index.html.twig', [
            'comentarios' => $comentariosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comentarios_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComentariosRepository $comentariosRepository): Response
    {
        $comentario = new Comentarios();
        $form = $this->createForm(ComentariosType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comentariosRepository->save($comentario, true);

            return $this->redirectToRoute('app_comentarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comentarios/new.html.twig', [
            'comentario' => $comentario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comentarios_show', methods: ['GET'])]
    public function show(Comentarios $comentario): Response
    {
        return $this->render('comentarios/show.html.twig', [
            'comentario' => $comentario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comentarios_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comentarios $comentario, ComentariosRepository $comentariosRepository): Response
    {
        $form = $this->createForm(ComentariosType::class, $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comentariosRepository->save($comentario, true);

            return $this->redirectToRoute('app_comentarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comentarios/edit.html.twig', [
            'comentario' => $comentario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comentarios_delete', methods: ['POST'])]
    public function delete(Request $request, Comentarios $comentario, ComentariosRepository $comentariosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comentario->getId(), $request->request->get('_token'))) {
            $comentariosRepository->remove($comentario, true);
        }

        return $this->redirectToRoute('app_comentarios_index', [], Response::HTTP_SEE_OTHER);
    }
}
