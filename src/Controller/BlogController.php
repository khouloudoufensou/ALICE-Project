<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\UploadService;
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
    public function index(ArticleRepository $articleRepository):Response
    {
       $articles = $articleRepository->findBy([],['createdAt' => 'desc']);

        
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
        $articles = $articleRepository->findRecentArticles();

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
        // dd($article);
        return $this->handleform($article, $request, true);
       
    }
     /**
     * @Route("/mon-espace/articles/modifier/{id}", name="membre_blog_update")
     */
    public function membreBlogUpdate(Article $article, Request $request)
    {
      return $this->handleform($article, $request, false);
    }

    public  function handleForm(Article $article, Request $request, bool $new)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();            
            // dd($image);
            if ($image) {
                $fileName = $this->uploadService->uploadImage($image, $article);
                $article->setImage($fileName);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            // $article->setImage($image);
            $em->flush();

            $this->addFlash('success', "L'article a bien été "  .  ($new ? 'crée' : 'modifié')); 
            return $this->redirectToRoute('membre_blog_update', ['id' => $article->getId() ]);
        }

        return $this->render('blog/membre/form.html.twig', [
            'form' => $form->createView(), 
            'article' => $article,
            'new' => $new
        ]);
        }

     /**
     * @Route("/mon-espace/articles/supprimer/{id}", name="membre_blog_remove")
     */
    public function membreBlogRemove(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        $this->addFlash('success', "L'article a bien été supprimé.");
        return $this->redirectToRoute('membre_blog_list');
    }
}
