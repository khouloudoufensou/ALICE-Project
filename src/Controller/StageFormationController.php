<?php

namespace App\Controller;

use App\Entity\StageFormation;
use App\Form\StageFormationType;
use App\Repository\StageFormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StageFormationController extends AbstractController
{

    // ---Front;


    /**
    * @Route("/list-stages-formations", name="list_stages_formations")
    */
    public function index(
        StageFormationRepository $stageFormationRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $StageFormation= $paginator->paginate(
            // attentions à la date des formations il me faut une condition
            // nbr de place dispo il faut fair une condition (dispo ou pas)
            $stageFormationRepository->findAll(),   
            $request->query->getInt('page',1),
            1 //1 article par page
        );

        return $this->render('stage_formation/index.html.twig', [
            'stageFormations' => $StageFormation
        ]);
    }

    /**
     * @Route("/stage_formation/{id}", name="stage_formation")
     */
    public function article(StageFormation $StageFormation, Request $request): Response
    {
        
        return $this->render('stage_formation/stageFormation.html.twig', [
            'stageFormation'=> $StageFormation
        ]);
    }

    
    // ---back

    // modifier
    /**
     * @Route("/admin/modifier/{id}", name="admin_update")
     */
    // public function adminUpdate(StageFormation $StageFormation, Request $request): Response
    // {
    //     return $this->handelForm($StageFormation, $request, false);
    // }

    // ajouter
    /**
    * @Route("/admin/ajouter", name="admin_add")
    */
    public function adminAdd(Request $request): Response
    {
        $StageFormation=new StageFormation();
        // dd($StageFormation);
        return $this->handelForm($StageFormation, $request, true);
    }

    public function handelForm(StageFormation $StageFormation, Request $request, bool $new)
    {
        
        $form=$this->createForm(StageFormationType::class, $StageFormation); 
        // dd($form);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            
           
            $em=$this->getDoctrine()->getManager();
            $em->persist($StageFormation); 
            $em->flush();

            $this->addFlash('success',"la formation a bien été " . ($new ? 'créé' : 'modifié'));
        }



        return $this->render('stage_formation/admin/form.html.twig', [
           'form'=> $form->createView(),
        //    'StageFormation' => $StageFormation,
           'new' => $new
        ]);
    }



}
