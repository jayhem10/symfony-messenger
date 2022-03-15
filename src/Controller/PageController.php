<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NotifierUserFormType;
use App\Message\UserNotificationMessage;
use App\Repository\FailedJobRepository;
use App\Service\AsyncMethodService;
use App\Service\UserNotifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $em, FailedJobRepository $failedJobRepository, AsyncMethodService $asyncMethodService): Response
    {
        // dd(unserialize("O:36:\"Symfony\\Component\\Messenger\\Envelope\":2:{s:44:\"\0Symfony\\Component\\Messenger\\Envelope\0stamps\";a:1:{s:46:\"Symfony\\Component\\Messenger\\Stamp\\BusNameStamp\";a:1:{i:0;O:46:\"Symfony\\Component\\Messenger\\Stamp\\BusNameStamp\":1:{s:55:\"\0Symfony\\Component\\Messenger\\Stamp\\BusNameStamp\0busName\";s:21:\"messenger.bus.default\";}}}s:45:\"\0Symfony\\Component\\Messenger\\Envelope\0message\";O:35:\"App\\Message\\UserNotificationMessage\":1:{s:43:\"\0App\\Message\\UserNotificationMessage\0userId\";i:11;}}"));

        $form= $this->createForm(NotifierUserFormType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $user = new User();
           $user->setEmail($data['email']);
           $user->setUsername($data['username']);
           $em->persist($user);
           $em->flush();

           $asyncMethodService->async(UserNotifierService::class, 'notify', [$user->getId()]);
            //Sans passer par le service global
            //$messageBus->dispatch(new UserNotificationMessage($user->getId()));

           $this->addFlash('success', 'La notification a bien été envoyée');
           return $this->redirectToRoute("home");
        }

        return $this->render('page/index.html.twig', [
            'form' => $form->createView(),
            'jobs' => $failedJobRepository->findAll()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, FailedJobRepository $failedJobRepository){
        $failedJobRepository->reject($id);
        $this->addFlash('success', 'la tâche a bien été supprimée');
        return $this->redirectToRoute('home');
    }

    #[Route('/retry/{id}', name: 'retry')]
    public function retry(int $id, FailedJobRepository $failedJobRepository, MessageBusInterface $messageBus){
        $message = $failedJobRepository->find($id)->getMessage();
        // dd($failedJobRepository->find($id));
        //Sans passer par la 
        $messageBus->dispatch($message);
        $failedJobRepository->reject($id);
        $this->addFlash('success', 'la tâche a bien été renvoyée');
        return $this->redirectToRoute('home');
    }

}
