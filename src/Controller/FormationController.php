<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Formation;
use App\Entity\Reservation;
use App\Form\AvisType;
use App\Form\ReservationType;
use App\Repository\FormationRepository;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FormationController extends AbstractController
{
    
    // Part in common

    /**
    * @Route("/list-stages-formations", name="list_stages_formations")
    */
    public function index(
        FormationRepository $FormationRepository,
        Request $request, 
        PaginatorInterface $paginator): Response
    {
        $StageFormation= $paginator->paginate(
            // nbr de place dispo il faut faire une condition (dispo ou pas)
            $FormationRepository->findEvent(date("Y-m-d H:i:s")),   
            $request->query->getInt('page',1),
            6 
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

        // Booking
        $booking = new Reservation();
        $formReservaion = $this->createForm(ReservationType::class, $booking);
        $formReservaion->handleRequest($request);

        
        if($formReservaion->isSubmitted() && $formReservaion->isValid()){
            // $this->isGranted('ROLE_MEMBER')
            if ($this->isGranted('ROLE_USER')) {
                $booking->setFormation($formation);
            $booking->setUser($this->getUser());
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success','Votre formation a bien été ajouté au panier');
            return $this->redirectToRoute('list_stages_formations');
            }else
            {
                return $this->redirectToRoute('app_login');
            }
            
        }
          

        // notice
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
            'form' => $form->createView(),
            'formReservaion' => $formReservaion->createView()
            
        ]);
    }  


    // Identified person
    // Notice part

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
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')")
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

    // Bascket
    /**
     * @Route("/panier", name="bascket")
     */
    public function bascket(): Response
    {   
        return $this->render('formation/panier.html.twig');
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(): Response
    {
        return $this->render('formation/checkout.html.twig');
    }

    // delete reservation
    /**
     * @Route("/reservation/supprimer/{id}", name="reservation_remove")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MEMBER')")
     */
    public function reservationRemove(Reservation $reservation, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete".$reservation->getId(), $request->get("_token") )) {
            // dd("suprimer");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
            
            $this->addFlash('success',"la réservation a bien été supprimé");
        }
        
        return $this->redirectToRoute('bascket');
    }
}
