<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UserNotificationMessage;
use App\Service\UserNotifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserNotificationMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private UserNotifierService $userNotifierService;

    public function __construct(EntityManagerInterface $em, UserNotifierService $userNotifierService)
    {
        $this->em = $em;   
        $this->userNotifierService = $userNotifierService;   
    }

    public function __invoke(UserNotificationMessage $message)
    {
        $user = $this->em->find(User::class, $message->getUserId());
        $userId = $user->getId();
        if($user !== null){
            $this->userNotifierService->notify($userId);
        }
         
    }
}
