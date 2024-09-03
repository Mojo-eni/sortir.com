<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AjaxEventController extends AbstractController
{
    #[Route('/get-data', name: 'get_data', methods: ['POST'])]
    public function getData(Request $request): JsonResponse
    {
        $campus = $request->request->get('campus');

        $data = match ($campus) {
            'campus 0' => [
                ['name' => 'Apple', 'details' => 'A sweet red fruit'],
                ['name' => 'Banana', 'details' => 'A yellow tropical fruit'],
            ],
            'campus 1' => [
                ['name' => 'Carrot', 'details' => 'A long orange vegetable'],
                ['name' => 'Lettuce', 'details' => 'A leafy green vegetable'],
            ],
            'campus 2' => [
                ['name' => 'Dog', 'details' => 'A loyal domestic animal'],
                ['name' => 'Cat', 'details' => 'A small, furry domestic animal'],
            ],
            'campus 3' => [
                ['name' => 'Bla', 'details' => 'A blabla'],
                ['name' => 'Blo', 'details' => 'A bloblo'],
            ],
            default => [],
        };

        return new JsonResponse($data);
    }
}
