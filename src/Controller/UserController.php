<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="users")
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request)
    {
        $list = $userRepository->findBy([], ["fullName" => "ASC"]);

        $users = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), //par defaut 1
            10 // le nombre par page
        );
        return $this->render('/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/delete/{id} ", name="user_delete")
     */
    public function edit(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $this->addFlash('success', "l'utilisateur a bien été supprimé");
        $em->flush();

        return $this->redirectToRoute('users');
    }
}
