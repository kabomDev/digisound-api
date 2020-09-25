<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TicketRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $userRepository, TicketRepository $ticketRepository, Request $request)
    {
        $admin  = $userRepository->findBy(['email' => 'admin@gmail.com']);
        //$list = $ticketRepository->findBy([], ["id" => "DESC"]);
        $beginDate = date('Y-m-01');
        //dd($beginDate);
        $endDate = date('Y-m-31');

        $list = $ticketRepository->findBetweenDate($beginDate, $endDate);
        //dd($tickets);

        $tickets = $this->paginator->paginate(
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
