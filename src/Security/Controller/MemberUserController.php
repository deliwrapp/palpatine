<?php

namespace App\Security\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/member/user")
 */
class MemberUserController extends AbstractController
{
    /**
     * @Route("/", name="MemberUsersList")
     */
    public function index(): Response
    {

        return $this->render('@security/user/member/index.html.twig', []);
    }
}
