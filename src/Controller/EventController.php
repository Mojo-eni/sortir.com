<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ListEventFormType;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlaceRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/event', name: 'app_event')]

class EventController extends AbstractController
{
    private $eventRepository;
    private $cityRepository;
    private $campusRepository;
    private $placeRepository;
    private $statusRepository;
    private $userRepository;

    /**
     * @param $eventRepository
     * @param $cityRepository
     * @param $campusRepository
     * @param $placeRepository
     * @param $statusRepository
     * @param $userRepository
     */
    public function __construct(EventRepository $eventRepository,CityRepository $cityRepository,CampusRepository $campusRepository,
                                PlaceRepository $placeRepository,StatusRepository $statusRepository,UserRepository $userRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->cityRepository = $cityRepository;
        $this->campusRepository = $campusRepository;
        $this->placeRepository = $placeRepository;
        $this->statusRepository = $statusRepository;
        $this->userRepository = $userRepository;
    }


    #[Route('/', name: '_list')]
    public function index(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $events = $this->eventRepository->findAll();
        $campuses = $this->campusRepository->findAll();
        $participants = $this->userRepository->findAll();


        $listForm = $this->createForm(ListEventFormType::class);
        $listForm->handleRequest($request);
        $user = $this->getUser();
        $serializedUser = $serializer->serialize($user, 'json', ['groups' => ['default']]);

        if ($listForm->isSubmitted() && $listForm->isValid()) {
            // Get the search query
            $query = $request->get('list_event_form');
            $events = $this->eventRepository->findBy(['campus' => $query['campus']]);
            // Filter the data
            if ($query['keyword']) {

                $events = array_filter($events, function ($item) use ($query) {
                    return stripos($item->getName(), $query['keyword']) !== false;
                });
            }

            if ($query['dateTo'] || $query['dateFrom']) {
                $events = array_filter($events, function ($item) use ($query) {
                    $eventDate = $item->getEventStart();
                    $startDate = $query['dateFrom'];
                    $endDate = $query['dateTo'];
                    if ($startDate && $endDate) {
                        return $eventDate >= $startDate && $eventDate <= $endDate;
                    } else if ($startDate) {
                        return $eventDate >= $startDate;
                    } else if ($endDate) {
                        return $eventDate <= $endDate;
                    }
                    return false;
                }
                );
            }
        }


        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
            'events' => $events,
            'campuses' => $campuses,
            'participants' => $participants,
            'listForm' => $listForm,
            'serializedUser' => $serializedUser,
        ]);
    }


    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $event = new Event();
        $event->setOrganizer($user);
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();
            $event->getParticipationDeadline()->setTime(0,0);
            $event->addParticipant($user);
            // TODO vérifier que la date limite d'inscription est avant la date de la sortie
            if ($eventForm->getClickedButton() === $eventForm->get('save')) {
                $event->setStatus($this->statusRepository->findOneBy(['name' => 'Créée']));
            } elseif ($eventForm->getClickedButton() === $eventForm->get('publish')) {
                $event->setStatus($this->statusRepository->findOneBy(['name' => 'Ouverte']));
            }
            try{
                $em->persist($event);
                $em->flush();
            } catch (ORMException $e) {
                var_dump($e->getMessage());
            }
            return $this->redirectToRoute('app_event_list');
        }
        return $this->render('event/create.html.twig', [
            'controller_name' => 'EventController',
            'eventForm' => $eventForm->createView()
        ]);
    }

    #[Route('/{id}', name: '_details')]
    public function details($id, Request $req, EventRepository $eRepo, UserRepository $uRepo, EntityManagerInterface $em): Response {
        $event = $eRepo->find($id);
        if($event) {


            $now = new \DateTime();

            return $this->render('event/details.html.twig', [
                'event' => $event, 'now' => $now
            ]);
        } else {
            return $this->redirectToRoute('app_event_list');
        }
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function edit($id, Request $req, EventRepository $eRepo, EntityManagerInterface $em): Response {
        $event = $eRepo->find($id);
        if (!$event){
            if ($this->getUser()->getUserIdentifier() !== $event->getOrganizer()->getUserIdentifier()
            || !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->redirectToRoute('app_event_list');
            }
        }

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($req);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();
            $event->getParticipationDeadline()->setTime(0,0);
            // TODO vérifier que la date limite d'inscription est avant la date de la sortie
            if ($eventForm->getClickedButton() === $eventForm->get('save')) {
                $event->setStatus($this->statusRepository->findOneBy(['name' => 'Créée']));
            } elseif ($eventForm->getClickedButton() === $eventForm->get('publish')) {
                $event->setStatus($this->statusRepository->findOneBy(['name' => 'Ouverte']));
            }
            try{
                $em->persist($event);
                $em->flush();
            } catch (ORMException $e) {
                var_dump($e->getMessage());
            }
            return $this->redirectToRoute('app_event_list');
        }
        return $this->render('event/edit.html.twig', [
            'controller_name' => 'EventController',
            'eventForm' => $eventForm->createView()
        ]);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete($id, Request $req, EventRepository $eRepo, EntityManagerInterface $em): Response {
        $event = $eRepo->find($id);
        if (!$event) {
            return $this->redirectToRoute('app_event_list');
        }
        if ($this->getUser() !== $event->getOrganizer()) {
            return $this->redirectToRoute('app_event_list');
        }
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('app_event_list');
    }


    #[Route('/{id}/participate', name: '_participate')]
    // src/Controller/SomeController.php

    public function participateEvent(int $id, EntityManagerInterface $em): Response
    {
        $event = $em->getRepository(Event::class)->find($id);
        $user = $this->getUser();

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        if ($event->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous participez déjà à cet événement.');
            return $this->redirectToRoute('app_event_details', ['id' => $id]);
        }

        if ($event->getStatus()->getName() !== 'Ouverte') {
            $this->addFlash('error', 'Les inscriptions sont fermées pour cet événement.');
            return $this->redirectToRoute('app_event_details', ['id' => $id]);
        }

        $now = new \DateTime();
        if ($now > $event->getParticipationDeadline()) {
            $this->addFlash('error', 'La date limite d\'inscription à cet événement est dépassée.');
            return $this->redirectToRoute('app_event_details', ['id' => $id]);
        }

        if ($event->getParticipants()->count() >= $event->getParticipantLimit()) {
            $this->addFlash('error', 'Le nombre limite est atteint.');
            return $this->redirectToRoute('app_event_details', ['id' => $id]);
        }

        $event->addParticipant($user);
        $em->persist($event);
        $em->flush();

        $this->addFlash('success', 'Vous avez été ajouté comme participant à cet événement !');
        return $this->redirectToRoute('app_event_details', ['id' => $id]);
    }

}