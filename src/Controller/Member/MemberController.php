<?php

namespace App\Controller\Member;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/espace-membre", name="member")
     */
    public function index(): Response
    {
        return $this->render('member/index.html.twig', [
           
        ]);
    }
}
