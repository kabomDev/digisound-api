<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtistController extends AbstractController
{
    /**
     * @Route("/artists", name="artists")
     */
    public function index(ArtistRepository $artistRepository, Request $request, PaginatorInterface $paginator)
    {
        $list = $artistRepository->findBy([], ["name" => "asc"]);

        $artists = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), //par defaut 1
            5 // le nombre par page
        );

        return $this->render('/artists/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     *@Route("/artists/add", name="artist_add")
     * @return void
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArtistType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();
            $em->persist($artist);
            $em->flush();
            $this->addFlash('success', "l'ajout d'un artiste a bien été éffectué");
            return $this->redirectToRoute('artists', ['id' => $artist->getId()]);
        }

        return $this->render('/artists/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     *@Route("/artists/edit/{id}", name="artist_edit") 
     */
    public function edit(Artist $artist, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            $this->addFlash('success', "la fiche de l'artiste a bien été modifié");
            return $this->redirectToRoute('artists');
        }

        return $this->render('/artists/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/artists/delete/{id}", name="artist_delete")
     */
    public function delete(Artist $artist, EntityManagerInterface $em)
    {
        $em->remove($artist);
        $em->flush();
        $this->addFlash('success', "l'artiste a bien été retiré");
        return $this->redirectToRoute('artists');
    }
}
