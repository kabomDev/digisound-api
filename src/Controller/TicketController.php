<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    /**
     * @Route("/tickets", name="tickets")
     */
    public function index(TicketRepository $ticketRepository, PaginatorInterface $paginator, Request $request)
    {
        $list = $ticketRepository->findBy([], ["id" => "DESC"]);

        $tickets = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), //par defaut 1
            10 // le nombre par page
        );
        return $this->render('tickets/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/tickets/delete/{id} ", name="ticket_delete")
     */
    public function delete(Ticket $ticket, EntityManagerInterface $em)
    {
        $em->remove($ticket);
        $this->addFlash('success', "le ticket a bien été supprimé");
        $em->flush();

        return $this->redirectToRoute('tickets');
    }
}
