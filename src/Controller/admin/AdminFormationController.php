<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
*  Require ROLE_ADMIN for *every* controller method in this class.
* @IsGranted("ROLE_ADMIN")
*/

class AdminFormationController extends AbstractController
{
    private $uploadService;

    public function __construct( UploadService $uploadService)
    {
        $this->uploadService= $uploadService;
    }

    // list

    /**
    * @Route("/a/admin/list", name="admin_list_formations")
    */
    public function index(
        FormationRepository $formationRepository
    ): Response
    {
        $formation= $formationRepository->findAll() ;
        return $this->render('formation/admin/list.html.twig', [
            'formations' => $formation
        ]);
    }


    
    public function handelForm(Formation $formation, Request $request, bool $new)
    {
        
        $form=$this->createForm(FormationType::class, $formation); 
        // dd($form);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {

            $image=$form->get('picture')->getData();

            if ($image)
            {
                $fileName= $this->uploadService->uploadImage($form->get('picture')->getData(), $formation);

                $formation->setPicture($fileName);
            }
                     
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($formation); 
            $entityManager->flush();

            $this->addFlash('success',"la formation a bien été " . ($new ? 'créé' : 'modifié'));
        }

        return $this->render('formation/admin/form.html.twig', [
           'form'=> $form->createView(),
           'formation' => $formation,
           'new' => $new
        ]);
    }

    // add
    /**
    * @Route("/a/admin/ajouter", name="admin_add")
    */
    public function adminAdd(Request $request): Response
    {
        $formation=new Formation();
        
        return $this->handelForm($formation, $request, true);
    }

    // update
    /**
     * @Route("/a/admin/modifier/{id}", name="admin_update")
     */
    public function adminUpdate(Formation $formation, Request $request): Response
    {
        return $this->handelForm($formation, $request, false);
    }

    // delete
    /**
     * @Route("/a/admin/supprimer/{id}", name="admin_remove")
     */
    public function adminRemove(Formation $formation, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete".$formation->getId(), $request->get("_token") )) {
            // dd("suprimer");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
            
            $this->addFlash('success',"la formation a bien été supprimé");
        }
        
        return $this->redirectToRoute('admin_list_formations');
    }

    
}
