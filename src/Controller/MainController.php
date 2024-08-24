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
    

    public function __construct(private ReservationService $reservationService)
    {
    }

    #[Route('/main', name: 'app_main')]
    public function index(Request $request): Response
    {
        $isLoggedIn     = $this->getUser() !== null;
        $reservation    = new Reservation();
        $form           = $this->createForm(ReservationType::class, $reservation);
        $form           ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user       = $this->getUser();
                $reservation->setEmail($user->getEmail());
                $this       ->reservationService->handleReservation($reservation, $user);
                $this       ->addFlash('success', 'Reservation made successfully!');
                return $this->redirectToRoute('app_main');
            } catch (\Exception $e) {
                $this       ->addFlash('error', 'There was an error with your reservation: ' . $e->getMessage());
                return $this->redirectToRoute('app_main');
            }

        }
        $errors = $form->getErrors(true, true);

        return $this->render(
            'main/index.html.twig', [
            'isLoggedIn'    => $isLoggedIn,
            'form'          => $form->createView(),
            'errors'        => $errors,
            ]
        );
    }

}
