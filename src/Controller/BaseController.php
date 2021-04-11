<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    public function header(string $routeName, ArticleRepository $articleRepository)
    {
        // $articles = [
        //     [ 'titre' => 'Article 1' ],
        //     [ 'titre' => 'Article 2' ],
        //     [ 'titre' => 'Article 3' ],
        // ];

        return $this->render('base/_header.html.twig', [
            'articles' => $articleRepository->findRecentArticles(3),
            'route_name' => $routeName,
        ]);
    }
    
    
    /**
     * @Route("/redirect-user", name="redirect_user")
     */
    public function redirectUser(): Response
    {
        // dd("hello");

        if ($this->isGranted('ROLE_ADMIN')){
            
            // dd("hello ADMIN");

            Return $this->redirectToRoute('admin');

        }elseif($this->isGranted('ROLE_MEMBER')){

            // dd("hello membre");

            Return $this->redirectToRoute('member');

        }else{
                // dd("hello Khouloud");
            
            Return $this->redirectToRoute('home');
        }
    }

   
    
     /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale(string $locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
        $pathHome = $this->generateUrl('home');
        $referer = $request->headers->get('referer', $pathHome);
        return $this->redirect($referer);
    }
}



