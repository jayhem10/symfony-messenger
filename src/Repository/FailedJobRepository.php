<?php

namespace App\Repository;

use App\Entity\FailedJob;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ListableReceiverInterface;

class FailedJobRepository
{

    private ListableReceiverInterface $receiver;

    public function __construct(ListableReceiverInterface $receiver)
    {
        $this->receiver = $receiver;
    }

    public function findAll() {
        return array_map(fn (Envelope $envelope) => new FailedJob($envelope), iterator_to_array($this->receiver->all()));
    }

    public function reject(string $id) {
        $this->receiver->reject($this->receiver->find($id));
    }

    public function find(string $id): FailedJob {
        return new FailedJob($this->receiver->find($id));
    }


}
