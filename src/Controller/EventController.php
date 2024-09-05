<?php

namespace App\Controller;

use App\Form\ListEventFormType;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $events = $this->eventRepository->findAll();
        $campuses = $this->campusRepository->findAll();
        $participants = $this->userRepository->findAll();


        $listForm = $this->createForm(ListEventFormType::class);
        $listForm->handleRequest($request);

        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
            'events' => $events,
            'campuses' => $campuses,
            'participants' => $participants,
            'listForm' => $listForm,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();
            $event->getParticipationDeadline()->setTime(0,0);
            // TODO vérifier que la date limite d'inscription est avant la date de la sortie ?
            // TODO récupérer les bons statut et organisateur
            $event->setStatus($this->statusRepository->findOneBy(['name' => 'status 0']));
            $event->setOrganizer($this->userRepository->findOneBy(['pseudo' => 'user 0']));
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

    #[Route('/get-place-info/{id}', name: 'get_place_info')]
    public function getPlaceInfo(Place $place): JsonResponse{
        return new JsonResponse([
            'address' => $place->getAddress(),
            'latitude' => $place->getLatitude(),
            'longitude' => $place->getLongitude()
        ]);
    }

    #[Route('/get-city-places/{id}', name: 'get_city_places')]
    public function getCityPlaces(Request $request, PlaceRepository $repo, string $id = '1'): Response
    {
        $characters = json_encode($repo->findByCityId($id));
        return $this->json($characters);
    }
}
