<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[Route('/main', name: 'app_main')]
    public function index(Request $request): Response
    {
        $isLoggedIn     = $this->getUser() !== null;
        $reservation    = new Reservation();
        $form           = $this->createForm(ReservationType::class, $reservation);
        $form           ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user       = $this->getUser();
            $success    = $this->reservationService->handleReservation($reservation, $user);

            if (!$success) {
                $this       ->addFlash('error', 'There was an error with your reservation.');
                return $this->redirectToRoute('app_main');
            }

            $this->addFlash('success', 'Reservation made successfully!');
        }

        return $this->render('main/index.html.twig', [
            'isLoggedIn'    => $isLoggedIn,
            'form'          => $form->createView(),
        ]);
    }

}
