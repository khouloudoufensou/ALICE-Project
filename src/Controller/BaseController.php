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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        
        return $this->render('base/_header.html.twig', [
            'articles' => $articleRepository->findRecentArticles(3),
            'route_name' => $routeName
            
        ]);
    }

    public function footer(string $routeName)
    {
        $newsletters = new Newsletters();

        $form = $this->createForm(NewslettersType::class, $newsletters);

        return $this->render('base/_footer.html.twig', [
            'form' => $form->createView(),
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
            
            dd("hello ADMIN");

            Return $this->redirectToRoute('admin');

        }elseif($this->isGranted('ROLE_MEMBER')){

            dd("hello membre");

            Return $this->redirectToRoute('member');

        }else{
                dd("hello Khouloud");
            
            Return $this->redirectToRoute('home');
        }
    }
  

      /**
     * @Route("/newsletters", name="newsletters", methods={"POST"})
     * 
     */
    public function newsletters(Request $request)
     {
        $newsletters = new Newsletters();

        $form = $this->createForm(NewslettersType::class, $newsletters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    

            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletters);
            $em->flush();

            $this->addFlash('success', "Merci de vous être inscrit.");
            
        }
        $referer = $request->headers->get('referer', $this->generateUrl('home'));
        return $this->redirect($referer."?news-sub=ok#footer");
     
    }

     /**
     * @Route("/newsletter/subscribes", name="newsletters_subscribes", methods={"POST"})
     * 
     */
    public function newsletterssubscribes(Request $request, ValidatorInterface $validator)
    {
        $email=$request->request->get("email");
        $newsletters = new Newsletters();

        $newsletters->setEmail($email);

        $errors=$validator->validate($newsletters);
        if (count($errors)===0) {

            $em=$this->getDoctrine()->getManager();
            $em->persist($newsletters);
            $em->flush();

            return $this->json([
                'success'=> true
            ]);
        }

        return $this->json([
            'success'=>false
        ]);

    }

}