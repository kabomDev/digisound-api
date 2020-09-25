<?php

namespace App\Doctrine\Listener;

use App\Entity\User;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class GetClientTicketListener
{
    protected UserRepository $repository;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function Prepersist(Ticket $ticket)
    {
        if (!$ticket->getTicketClient()) {
            $ticket->setTicketClient($this->security->getUser());
        }
    }
}
