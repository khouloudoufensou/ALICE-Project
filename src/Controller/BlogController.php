<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #-- Front

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
       $articles = $repo->findBy([],['createdAt' => 'desc']);

        
        return $this->render('blog/index.html.twig', [
            'articles' => $articles
         ]);
    }

    /**
     * @Route("/article/{id}", name="blog_article")
     */
    public function article(Article $article)
     {
        
        return $this->render('blog/article.html.twig', [
            'article' => $article
        ]);
    }

    #-- Back
     /**
     * @Route("/mon-espace/articles", name="membre_blog_list")
     */
    public function membreBlogList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('blog/membre/list.html.twig', [
            'articles' => $articles
        ]);
    }
    
    /**
     * @Route("/mon-espace/articles/nouveau", name="membre_blog_create")
     */
    public function membreBlogCreate(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', "L'article a bien été crée.");
            return $this->redirectToRoute('membre_blog_list');
        }

        return $this->render('blog/membre/form.html.twig', [
            'form' => $form->createView(), 
        ]);
        
        // handleForm($article, $request, true);;
    }


}
