<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class UserNotifierService
{
    private $mailer;
    private $em;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;

    }

    public function notify(int $userId):void
    {
        // sleep(30);
        // throw new \Exception('Message non envoyé');
        $user = $this->em->find(User::class, $userId);
        $email = (new TemplatedEmail())
            ->from('newsletter@site.fr')
            ->to($user->getEmail())
            ->subject('test envoi mail')
            ->htmlTemplate('emails/notification.html.twig')
            ->context(compact('user'))
        ;
        $this->mailer->send($email);

        $user->setIsNotifier(1);
        $this->em->flush();
    }
}