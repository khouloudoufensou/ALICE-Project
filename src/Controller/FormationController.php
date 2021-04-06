<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Formation;
use App\Entity\User;
use App\Form\AvisType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
    public function formation(Formation $formation, Request $request): Response
    {
        // dd($user);
        // dd($this->getUser());
       
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $avis->setFormation($formation);
            $avis->setUser($this->getUser());

            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avis);
            $entityManager->flush();

            $this->addFlash('success','Merci pour votre avis');
            return $this->redirectToRoute('formation', ['id' => $formation->getId()]);
        }

        
        return $this->render('formation/formation.html.twig', [
            'formation'=> $formation,
            'form' => $form->createView()
        ]);
    }  



    public function handelForm(Avis $avis, Request $request)
    {
        
        $form=$this->createForm(AvisType::class, $avis); 
        // dd($form);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {

               
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($avis); 
            $entityManager->flush();

            $this->addFlash('success',"Votre avis a bien été modifié.");
        }

        return $this->render('formation/avis/form.html.twig', [
           'form'=> $form->createView(),
           'avis' => $avis
        ]);
    }

    

    // update
    /**
     * @Route("/avis/modifier/{id}", name="notice_update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')")
     */
    public function noticeUpdate(Avis $avis, Request $request): Response
    {
        return $this->handelForm($avis, $request);
    }

    // delete
    /**
     * @Route("/avis/supprimer/{id}", name="notice_remove")
     * 
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')")
     * 
     * 
     */
    public function noticeRemove(Avis $avis, Request $request): Response
    {
        // dd($avis->getFormation()->getId());
        if ($this->isCsrfTokenValid("delete".$avis->getId(), $request->get("_token") )) {
            // dd("suprimer");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avis);
            $entityManager->flush();
            
            $this->addFlash('success',"l'avis a bien été supprimé");
        }
        
        return $this->redirectToRoute('formation', [
            'id' => $avis->getFormation()->getId()
            ]);
    }



}
