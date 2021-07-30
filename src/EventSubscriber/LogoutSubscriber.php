<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    private $urlGenerator ; 

    public function __construct(UrlGeneratorInterface $urlGenerator )
    {
        $this->urlGenerator=$urlGenerator ; 
    }

    public function onLogoutEvent(LogoutEvent $event)
    {
       $session=  $event->getRequest()->getSession(); 
       $session->getFlashBag()->add('success','Logged out succesfully');
       $url=$this->urlGenerator->generate('app_home');
     
       $event->setResponse(new RedirectResponse($url));
     }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
