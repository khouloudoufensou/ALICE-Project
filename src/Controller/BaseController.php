<?php

namespace App\Controller;

use App\Entity\Newsletters;
use App\Form\NewslettersType;
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

      /**
     * @Route("/newsletters", name="newsletters")
     */
//     public function article(Newsletters $newsletters, Request $request)
//      {
//         $newsletters = (new Newsletters());
//         $form = $this->createForm(NewslettersType::class, $newsletters);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $newsletters->setNewsletters($newsletters);

//             $em = $this->getDoctrine()->getManager();
//             $em->persist($newsletters);
//             $em->flush();

//             $this->addFlash('success', "Merci de vous être inscrit.");
//             return $this->redirectToRoute('home', [ 'id' => $newsletters->getId() ]);
//         }

//         return $this->render('base/newsletters.html.twig', [
//             'newsletters' => $newsletters,
//             'form' => $form->createView(),
//         ]);
//     }


}