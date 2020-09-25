<?php

namespace App\EventSubscriber;

use App\Entity\Ticket;
use Endroid\QrCode\QrCode;
use Doctrine\Common\EventSubscriber;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MailerSubscriber implements EventSubscriber
{

    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents()
    {
        return [
            'postPersist'
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs)
    {

        $ticket = $lifecycleEventArgs->getObject();

        //si $ticket n'est pas une instance de la classe ticket, on y fait pas attention
        if (!$ticket instanceof Ticket) {
            return;
        }

        $event = $ticket->getEventName();
        $user = $ticket->getTicketClient();

        //creation du qrcode
        $name = $user->getFullName();
        $eventName = $event->getTitle();
        $quantity = $ticket->getQuantity();

        $qrCode = new QrCode($name . $eventName . $quantity . '.png');
        $qrCode->setSize(200);
        $qrCode->setEncoding('UTF-8');
        $dataUri = $qrCode->writeDataUri();
        //dd($dataUri);

        //on envois un mail au client
        $email = new TemplatedEmail();
        $email
            ->from('notification@digisound.com')
            ->to($user->getEmail())
            ->subject(sprintf("Votre achat pour l'évènement `%s`", $event->getTitle()))
            ->htmlTemplate('emails/new-command.html.twig')
            ->context([
                'event' => $event,
                'user' => $user,
                'ticket' => $ticket,
                'billet' => $dataUri
            ]);
        $this->mailer->send($email);
    }
}
