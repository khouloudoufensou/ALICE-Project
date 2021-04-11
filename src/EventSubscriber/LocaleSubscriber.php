<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        // dd('kernel.request');
        //    dd($event);

        // dd($event->getRequest()->getSession()->get('_locale'));

        $request = $event->getRequest();
        $locale = $request->getSession()->get('_locale', 'fr');
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
