<?php

namespace App\Security\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MemberUserController
 * @package App\Security\Controller
 * @Route("/member/user")
 */
class MemberUserController extends AbstractController
{
    
    /**
     * User List Index
     * 
     * @Route("/", name="MemberUsersList")
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('@security/user/member/account-show.html.twig', []);
    }
}
