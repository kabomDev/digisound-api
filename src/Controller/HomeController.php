<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ticket;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\ArtistRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Signature\CreatedJWS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(EventRepository $eventRepository, ArtistRepository $artistRepository, UserRepository $userRepository, TicketRepository $ticketRepository, PaginatorInterface $paginator, Request $request)
    {
        $admin  = $userRepository->findBy(['email' => 'admin@gmail.com']);
        //$list = $ticketRepository->findBy([], ["id" => "DESC"]);
        $beginDate = date('Y-m-01');
        //dd($beginDate);
        $endDate = date('Y-m-31');

        $list = $ticketRepository->findBetweenDate($beginDate, $endDate);
        //dd($tickets);
        $tickets = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), //par defaut 1
            10 // le nombre par page
        );
        return $this->render('admin/home.html.twig', [
            'admin' => $admin,
            'tickets' => $tickets
        ]);
    }
}
