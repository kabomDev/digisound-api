<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Artist;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;

class EventController extends AbstractController
{


    /**
     * Accueil
     * @Route("/events", name="events")
     */
    public function home(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request)
    {
        $list = $eventRepository->findBy([], ["startDate" => "ASC"]);

        $events = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), //par defaut 1
            5 // le nombre par page
        );
        return $this->render('/events/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/events/add", name="event_add")
     */
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $event = $form->getData();
            $artists = $form->get('artists')->getData();

            $file = $form['image']->getData();
            //dd($file);
            $fileName = $file->getClientOriginalName(); //=> lefichier.jpg;

            //enregistre dans le dossier images
            $file->move($this->getParameter('upload_directory'), $fileName);
            $event->setImage($fileName);
            //dd($event);

            if ($artists) {
                foreach ($artists as $artist) {

                    $event->addArtist($artist);
                }
            }
            $em->flush();
            $this->addFlash('success', "l'evènement a bien été crée");
            return $this->redirectToRoute('events');
        }
        return $this->render('/events/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/events/edit/{id}", name="event_edit")
     */
    public function edit(Event $event, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        //dd($event);
        $image = $event->getImage();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = $form->getData();
            $event->setImage($image);

            $artists = $form->get('artists')->getData();


            $file = $form['image']->getData();
            //dd($event);
            if ($file) {
                $fileName = $file->getClientOriginalName(); //=> lefichier.jpg;
                //dd($fileName);

                //enregistre dans le dossier images
                $file->move($this->getParameter('upload_directory'), $fileName);
                $event->setImage($fileName);
                //dd($event);
            }

            $em->flush();
            $this->addFlash('success', "l'evènement a bien été modifié");
            return $this->redirectToRoute('events');
        }

        return $this->render('/events/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/events/delete/{id}", name="event_delete")
     */
    public function delete(Event $event, EntityManagerInterface $em)
    {
        $em->remove($event);
        $this->addFlash('success', "l'evènement a bien été supprimé");
        $em->flush();

        return $this->redirectToRoute('events');
    }
}
