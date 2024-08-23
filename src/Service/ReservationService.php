<?php

namespace App\Service;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ReservationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handleReservation(Reservation $reservation, ?UserInterface $user): bool
    {
        if (!$user) {
            return false;
        }

        $existingReservation = $this->entityManager->getRepository(Reservation::class)
            ->findOneBy(['email' => $reservation->getEmail()]);

        if ($existingReservation) {
            return false;
        }

        if ($reservation->getDate() < new \DateTime('today')) {
            return false;
        }

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return true;
    }
}
