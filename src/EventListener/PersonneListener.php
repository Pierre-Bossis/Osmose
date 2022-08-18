<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;
use App\Event\ListAllPersonnesEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class PersonneListener
{

    public function __construct(private LoggerInterface $logger)
    {

    }



    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug("Coucou je suis en train d'écouter l'évènement personne.add et une personne vient d'être ajoutée et c'est : ". $event->getPersonne()->getName());
    }

    public function onListAllPersonnes(ListAllPersonnesEvent $event)
    {
        $this->logger->debug("Le nombre de personnes dans la base est de : ". $event->getNbPersonne());
    }

    public function onListAllPersonnes2(ListAllPersonnesEvent $event)
    {
        $this->logger->debug("le second listener avec le nbre ". $event->getNbPersonne());
    }

    public function logKernelRequest(KernelEvent $event)
    {
       dd($event->getRequest());
    }

}