<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/comment')]
class ApiCommentController extends AbstractController
{
    #[Route('/list', name: 'app_apicomment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): JsonResponse
    {
        //la variable $comment guarda todos los registros de la variable ($commentRepository) que es el repositorio de comentarios
        $comment = $commentRepository->findAll();
        //declaramos un variable data con un array vacio
        $data = [];
        // recorremos cada uno de los registros de la variable comment 
        foreach ($comment as $p) {
        //la variable data iremos almacenando los registros de los campos que iremos consultando
        //para obtener estos datos usamos los metodos get con su respectivos campos
            $data[] = [
                'id' => $p->getId(),
                'comment' => $p->getComment(),
                'date' => $p->getDate(),
            ];
        }
        //retornamos estos registros en una respuesta tipo json, con estatus 200 que significa que todo esta correcto 
        //las headers se utiliza para permitir que cualquier origen pueda acceder a los recursos de tu servidor

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/create', name: 'app_apicomment_create', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
	{
    //la variable comentario se le asigna una nueva clase comentario
    $comment = new Comment();

    
    //
    $comment = $request->get('comment');
    $date = $request->get('date');
    // // $date = $request->request->get('date');
    $comment->setComment($comment);
    $comment->setDate($date);
    // // $proyect->setImage($image);
    // // $proyect->setDate($date);
    $entityManager->persist($comment);
    $entityManager->flush();

    return $this->json(['message' => 'Comment created'], 201, ['Access-Control-Allow-Origin' => '*']);
}

}