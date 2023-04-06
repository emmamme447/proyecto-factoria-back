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

#[Route('/api')]
class ApiCommentController extends AbstractController
{
    #[Route('/comment', name: 'app_apicomment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findAll();

        $data = [];

        foreach ($comment as $p) {
            $data[] = [
                'id' => $p->getId(),
                'title' => $p->getName(),
                'position' => $p->getPosition(),
                'comment' => $p->getComment(),
                'date' => $p->getDate(),
            ];
        }

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/list', name: 'app_apicomment_create', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
	{
    $comment = new Comment();
    $name = $request->request->get('name');
    $position = $request->request->get('position');
    $comment = $request->files->get('comment');
    $date = $request->files->get('date');
    // // $date = $request->request->get('date');
    $comment->setName($name);
    $comment->setPosition($position);
    $comment->setComment($comment);
    $comment->setDate($date);
    // // $proyect->setImage($image);
    // // $proyect->setDate($date);
    $entityManager->persist($comment);
    $entityManager->flush();

    return $this->json(['message' => 'Comment created'], 201, ['Access-Control-Allow-Origin' => '*']);
}

}
