<?php

namespace App\Service;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ReservationService
{
    

    public function __construct(private EntityManagerInterface $entityManager){}

    public function handleReservation(Reservation $reservation, ?UserInterface $user):bool
    {
        try {
            if (!$user) {
                throw new \Exception('User is not logged in.');
            }
    
            $existingReservation = $this->entityManager->getRepository(Reservation::class)
                ->findOneBy(['email' => $user->getEmail()]);
    
            if ($existingReservation) {
                throw new \Exception('You have already made a reservation.');
            }
    
            if ($reservation->getDate() < new \DateTime('today')) {
                throw new \Exception('Reservation date cannot be in the past.');
            }
    
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
    
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
