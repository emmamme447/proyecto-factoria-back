<?php

namespace App\Controller;

use App\Entity\Period;
use App\Form\PeriodType;
use App\Repository\PeriodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/period')]
class PeriodController extends AbstractController
{
    #[Route('/', name: 'app_period_index', methods: ['GET'])]
    public function index(PeriodRepository $periodRepository): Response
    {
        return $this->render('period/index.html.twig', [
            'periods' => $periodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_period_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PeriodRepository $periodRepository): Response
    {
        $period = new Period();
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $periodRepository->save($period, true);

            return $this->redirectToRoute('app_period_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('period/new.html.twig', [
            'period' => $period,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_period_show', methods: ['GET'])]
    public function show(Period $period): Response
    {
        return $this->render('period/show.html.twig', [
            'period' => $period,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_period_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Period $period, PeriodRepository $periodRepository): Response
    {
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $periodRepository->save($period, true);

            return $this->redirectToRoute('app_period_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('period/edit.html.twig', [
            'period' => $period,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_period_delete', methods: ['POST'])]
    public function delete(Request $request, Period $period, PeriodRepository $periodRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$period->getId(), $request->request->get('_token'))) {
            $periodRepository->remove($period, true);
        }

        return $this->redirectToRoute('app_period_index', [], Response::HTTP_SEE_OTHER);
    }
}
