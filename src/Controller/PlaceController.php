<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/place', name: 'app_place')]
class PlaceController extends AbstractController
{
    #[Route('/create/{from}', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em, String $from): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $event = $placeForm->getData();
            try{
                $em->persist($place);
                $em->flush();
            } catch (ORMException $e) {
                var_dump($e->getMessage());
            }
            if ($from =='new') {
                return $this->redirectToRoute('app_event_create');
            } else {
                return $this->redirectToRoute('app_event_edit', ["id" => $from]);
            }
        }
        return $this->render('place/create.html.twig', [
            'controller_name' => 'EventController',
            'placeForm' => $placeForm->createView(),
            'from' => $from,
        ]);
    }
}
