<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('base/home.html.twig', [
           
        ]);
    }

    
    /**
     * @Route("/a-propos", name="about")
     */
    public function about(): Response
    {
        return $this->render('base/about.html.twig');
    }

    // public function header(string $routeName, ArticleRepository $articleRepository)
    public function header(string $routeName)
    {
    

        return $this->render('base/_header.html.twig', [
            // 'articles' => $articleRepository->findRecentArticles(3),
            'route_name' => $routeName,
        ]);
    }
    
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
