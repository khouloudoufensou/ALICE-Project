<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Service\UploadService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    private $uploadService;

    public function __construct( UploadService $uploadService)
    {
        $this->uploadService= $uploadService;
    }

    // ---Front;


    /**
    * @Route("/list-stages-formations", name="list_stages_formations")
    */
    public function index(
        FormationRepository $FormationRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $StageFormation= $paginator->paginate(
            // nbr de place dispo il faut fair une condition (dispo ou pas)
            $FormationRepository->findEvent(date("Y-m-d H:i:s")),   
            $request->query->getInt('page',1),
            1 //1 article par page
        );

        return $this->render('formation/index.html.twig', [
            'formations' => $StageFormation
        ]);
    }

    /**
     * @Route("/formation/{id}", name="formation")
     */
    public function article(Formation $Formation, Request $request): Response
    {
        
        return $this->render('formation/formation.html.twig', [
            'formation'=> $Formation
        ]);
    }

    
    // ---back

    public function handelForm(Formation $Formation, Request $request, bool $new)
    {
        
        $form=$this->createForm(FormationType::class, $Formation); 
        // dd($form);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {

            $image=$form->get('picture')->getData();

            if ($image)
            {
                $fileName= $this->uploadService->uploadImage($form->get('picture')->getData(), $Formation);

                $Formation->setPicture($fileName);
            }
                     
            $em=$this->getDoctrine()->getManager();
            $em->persist($Formation); 
            $em->flush();

            $this->addFlash('success',"la formation a bien été " . ($new ? 'créé' : 'modifié'));
        }

        return $this->render('formation/admin/form.html.twig', [
           'form'=> $form->createView(),
           'formation' => $Formation,
           'new' => $new
        ]);
    }

    // add
    /**
    * @Route("/admin/ajouter", name="admin_add")
    */
    public function adminAdd(Request $request): Response
    {
        $Formation=new Formation();
        // dd($StageFormation);
        return $this->handelForm($Formation, $request, true);
    }

    // update
    /**
     * @Route("/admin/modifier/{id}", name="admin_update")
     */
    public function adminUpdate(Formation $Formation, Request $request): Response
    {
        return $this->handelForm($Formation, $request, false);
    }

    // delete
    /**
     * @Route("/admin/supprimer/{id}", name="admin_remove")
     */
    public function membreBlogRemove(Formation $Formation): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($Formation);
        $em->flush();

        $this->addFlash('success',"la formation a bien été supprimé");
        return $this->redirectToRoute('list_stages_formations');
    }

    


}
