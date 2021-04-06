<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    


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
            1 
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


}
