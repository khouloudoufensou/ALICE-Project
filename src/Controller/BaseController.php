<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/base", name="base")
     */
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
        ]);
    }

    // public function header(string $routeName): Response
    // {

    //     return $this->render('base/_header.html.twig', [
    //         'route_name' => $routeName
    //     ]);
     
    // }

    /**
     * @Route("/redirect-user", name="redirect_user")
     */
    public function redirectUser(): Response
    {
        
        if ($this->isGranted('ROLE_ADMIN')){
            
            Return $this->redirectToRoute('admin_list_formations');

        }elseif($this->isGranted('ROLE_MEMBER')){
            Return $this->redirectToRoute('list_stages_formations');

        }else{
            Return $this->redirectToRoute('home');
        }
    }
}
