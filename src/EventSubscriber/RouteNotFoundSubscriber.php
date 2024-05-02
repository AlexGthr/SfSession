<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use App\EventSubscriber\RouteNotFoundSubscriber;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteNotFoundSubscriber implements EventSubscriberInterface
{

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) 
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exeption = $event->getThrowable();

        if ($exeption instanceof NotFoundHttpException) {
            // Genere une url pour la redirection
            $redirectUrl = $this->urlGenerator->generate('app_home');

            // Crée une réponse pour l'url
            $response = new RedirectResponse($redirectUrl);

            // Set la reponse à l'event
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

}