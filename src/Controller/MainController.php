<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/main', name: 'app_main')]
    public function index(Request $request): Response
    {

        $isLoggedIn     = $this->getUser() !== null;
        $reservation    = new Reservation();
        $user           = $this->getUser();
        $form           = $this->createForm(ReservationType::class, $reservation);
        $form           ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user) {
                        $this->addFlash('error', 'Please register first.');
                return  $this->redirectToRoute('app_register');
            }

            $existingReservation = $this->entityManager->getRepository(Reservation::class)
                                 ->findOneBy(['email' => $reservation->getEmail()]);

            if ($existingReservation) {
                    $this->addFlash('error', 'You has already made a reservation.');
                return  $this->redirectToRoute('app_main');
            }

            if ($reservation->getDate() < new \DateTime('today')) {
                    $this->addFlash('error', 'The date must be today or later.');
                return  $this->redirectToRoute('app_main');
            }

            $this   ->entityManager->persist($reservation);
            $this   ->entityManager->flush();
        }
        return  $this->render('main/index.html.twig', [
            'isLoggedIn' => $isLoggedIn,
            'form'       => $form->createView(),
        ]);
    }



    #[Route('/main/{id}', name: 'user_main')]
    public function user(int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }

        $user = $this->entityManager->getRepository(User::class)->find($id);

        return  $this->render('user/index.html.twig', [
            'userId' => $user->getId(),
        ]);
    }
}
