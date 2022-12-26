<?php

namespace App\EventSubscriber;

use App\EventListener\ProductListener;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->sendEmail();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->sendEmail();
    }

    /**
     * @return mixed
     */
    public function sendEmail()
    {
        $mailer = new \Swift_Mailer();
        $message = new \Swift_Message('Notification about process product');
        $message->setFrom($_ENV['ADMIN_EMAIL'])
            ->setTo($_ENV['ADMIN_EMAIL'])
            ->setBody('The email is sent')
        ;

        return $mailer->send($message);
    }
}