<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AjaxEventController extends AbstractController
{
    #[Route('/get-data/{campusId}', name: 'get_list_from_campus')]
    public function getData(SerializerInterface $serializer, EventRepository $eventRepository, $campusId = 1): JsonResponse
    {
        $events = $eventRepository->findBy(['campus' => $campusId]);
        $data = $serializer->serialize($events, 'json', ['groups' => 'default']);
        return JsonResponse::fromJsonString($data);
    }

    #[Route('/sort-data/{keyword}', name: 'filter-keyword')]
    public function filterKeyword(EventRepository $eventRepository, $id = 1): JsonResponse
    {
        $events = $eventRepository->findByCampusId($id);
        return new JsonResponse($events);
    }

    #[Route('/get-place-info/{id}', name: 'get_place_info')]
    public function getPlaceInfo(Place $place, $id = 1): JsonResponse{
        return new JsonResponse([
            'address' => $place->getAddress(),
            'latitude' => $place->getLatitude(),
            'longitude' => $place->getLongitude()
        ]);
    }

    #[Route('/get-city-info/{id}', name: 'get_city_info')]
    public function getCityInfo(City $city, $id = 1): JsonResponse{
        return new JsonResponse([
            'zipcode' => $city->getZipcode()
        ]);
    }

    #[Route('/get-city-places/{id}', name: 'get_city_places')]
    public function getCityPlaces(Request $request, PlaceRepository $repo, $id = '1'): Response
    {
        $places = json_encode($repo->findByCityId($id));
        return $this->json($places);
    }
}
