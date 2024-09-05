<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AjaxEventController extends AbstractController
{
    #[Route('/get-data/{id}', name: 'get_list_from_campus')]
    public function getData(EventRepository $eventRepository, $id = 1): JsonResponse
    {
        $events = $eventRepository->findByCampusId($id);
        return new JsonResponse($events);
    }
}
