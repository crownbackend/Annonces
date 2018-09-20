<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SendMailSubscriber implements EventSubscriberInterface
{
    public function onKernelController(FilterControllerEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.controller' => 'onKernelController',
        ];
    }
}
