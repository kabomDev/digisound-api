<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JwtDataSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::JWT_CREATED => 'addFullName'
        ];
    }

    public function addFullName(JWTCreatedEvent $event)
    {
        //on obtient les infos du user
        /**@var \App\Entity\User */
        $user = $event->getUser();
        //on obtient le token
        $data = $event->getData();
        $data['id'] = $user->getId();
        $data['fullName'] = $user->getFullName();
        $event->setData($data);
    }
}
