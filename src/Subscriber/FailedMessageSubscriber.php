<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Texter;

class FailedMessageSubscriber implements EventSubscriberInterface {

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(){
        return[
            WorkerMessageFailedEvent::class => 'onMessageFailed'
        ];
    }


    public function onMessageFailed(WorkerMessageFailedEvent $event){
        $message = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = ( new Email())
            ->from('noreply@server.fr')
            ->to('noble.jka@protonmail.com')
            ->text(<<<TEXT
            Une erreur est survenue lors du traitement de votre demande
            {$message}
            {$trace}

            TEXT
    );
        $this->mailer->send($email);
}

}