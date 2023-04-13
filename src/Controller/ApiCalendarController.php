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
                'startDate' => $p->getStartDate(),
                'finishDate' => $p->getFinishDate(),
                'recipient' => $p->getRecipient(),
            ];
        }

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/calendar/new', name: 'app_apicalendar_create', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
	{
    $calendar = new Calendar();
    //para obtener el contenido del cuerpo de la solicitud en forma de cadena
    $content = $request->getContent();
    //para convertirlo en un arreglo asociativo
    $data = json_decode($content);
    // Buscar los valores decodificados en data
    $calendar->setTitle($data->title);
    $startDate = new \DateTime($data->startDate);
    $calendar->setStartDate($startDate);
    $finishDate = new \DateTime($data->finishDate);
    $calendar->setFinishDate($finishDate);
    $calendar->setRecipient($data->recipient);
    //Guardar el objeto calendar en base de datos 
    $entityManager->persist($calendar);
    $entityManager->flush();
    //retorna un mensaje cuando el calendario ha sido creado
    return $this->json(['message' => 'Calendar created'], 201, ['Access-Control-Allow-Origin' => '*']);
}

}