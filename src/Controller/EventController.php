<?php

namespace App\Controller;

use App\Form\ListEventFormType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlaceRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
}
