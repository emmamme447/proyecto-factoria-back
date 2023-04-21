<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\EmployeeRepository;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ChecktokenController extends AbstractController

{
    #[Route('/checktoken', name:'check_token')]
<<<<<<< HEAD

=======
>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b
    public function index(Request $request, UserRepository $userRepository, EmployeeRepository $employeeRepository): Response
    {
        //$em = $this->getDoctrine()->getManager();
        if($request->query->get('bearer')) {
            $token = $request->query->get('bearer');
        }else {
            return $this->redirectToRoute('login');
        }
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

<<<<<<< HEAD
        // dump($jwtPayload);die;
    
        $user = $userRepository->findOneByEmail($jwtPayload->username);

        // dump($user->getRoles());die;
=======
        //dump($jwtPayload);die;

        $user = $userRepository->findOneByEmail($jwtPayload->username);
        
        //dump($user->getRoles());die;
>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b

        if(!$user) {
            return $this->redirectToRoute('login');
        }
<<<<<<< HEAD
=======

>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b
        $employee = $employeeRepository->findOneByEmail($jwtPayload->username);
        $response = new Response();
        $response->setContent(json_encode([
            'auth' => 'ok',
            'email' => $user->getEmail(),
<<<<<<< HEAD
            'rol' => $user->getRol(), 
=======
            'rol' => $user->getRol(),
>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b
            'id' => $employee->getId(),
        ]));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('pass', 'ok');
        $response->headers->set('email', $user->getEmail());
<<<<<<< HEAD
        // ¿Una vez con esto la vista puede logarse?
=======

        // ¿Una vez con esto la vista puede logarse?

>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b
        $response->headers->setCookie(new Cookie('Authorization', $token));
        $response->headers->setCookie(new Cookie('BEARER', $token));

<<<<<<< HEAD

=======
        return $response;
    }
>>>>>>> dc1d1634116b997d494b45b961dda408cc64138b
    #[Route('/api/test', name:'check_api')]
    public function checktoken2(Request $request, UserRepository $userRepository): Response
    {
        return $this->json(['pass'=> 'Acceso permitido por token'], $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}