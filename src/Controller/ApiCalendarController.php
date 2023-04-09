<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api')]
class ApiCalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_apicalendar_index', methods: ['GET'])]
    public function index(CalendarRepository $calendarRepository): Response
    {
        $calendar = $calendarRepository->findAll();

        $data = [];

        foreach ($calendar as $p) {
            $data[] = [
                'id' => $p->getId(),
                'title' => $p->getTitle(),
                'subject' => $p->getSubject(),
                'recipientName' => $p->getRecipientName(),
                'reciepientEmail' => $p->getRecipientEmail(),
                'date' => $p->getDate(),
                'status' => $p->getStatus(),
            ];
        }

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/new', name: 'app_apicalendar_create', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
	{
    $calendar = new Calendar();
    $title = $request->request->get('title');
    $subject = $request->request->get('subject');
    $recipient_name = $request->files->get('recipient_name');
    $recipient_email = $request->files->get('recipient_email');
    $date = $request->files->get('date');
    $status = $request->files->get('status');
    // // $date = $request->request->get('date');
    $calendar->setTitle($title);
    $calendar->setSubject($subject);
    $calendar->setRecipientName($recipient_name);
    $calendar->setRecipientEmail($recipient_email);
    $calendar->setDate($date);
    $calendar->setStatus($status);
    // // $proyect->setImage($image);
    // // $proyect->setDate($date);
    $entityManager->persist($calendar);
    $entityManager->flush();

    return $this->json(['message' => 'Calendar created'], 201, ['Access-Control-Allow-Origin' => '*']);
}

}

