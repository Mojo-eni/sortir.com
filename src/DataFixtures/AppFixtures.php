<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private readonly Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void {
        $this->addUsers(5, $manager);
        $this->addCities(5, $manager);
        $this->addStatuses($manager);
        $this->addCampuses($manager);
        $this->addPlaces(10, $manager);
        $this->addEvents(10, $manager);
    }



    private function addCities(int $number, ObjectManager $manager): void {
        for ($i = 0; $i < $number; $i++) {
            $city = new City();
            $city->setName('city '.$i);
            $city->setZipcode(mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9));
            $manager->persist($city);
        }
        $manager->flush();
    }

    private function addStatuses(ObjectManager $manager): void {
        // create 5 cities
        for ($i = 0; $i < 6; $i++) {
            $status = new Status();
            $status->setName('status '.$i);
            $manager->persist($status);
        }
        $manager->flush();
    }

    private function addCampuses(ObjectManager $manager): void {
        //accès à un repository depuis l'entitymanager
        $cities = $manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < 4; $i++) {
            $campus = new Campus();
            $campus->setName('campus '.$i);
            $campus->setCity($this->faker->randomElement($cities));
            $manager->persist($campus);
        }
        $manager->flush();
    }

    private function addUsers(int $number, ObjectManager $manager): void
    {
        for ($i = 0; $i < $number; $i++) {
            $user = new User();
            $user->setPseudo("user ".$i)
                ->setLastName($this->faker->lastName())
                ->setFirstName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setActif(true)
                ->setRoles(['ROLE_USER'])
                ->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        '1234'
                    )
                );

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function addPlaces(int $number, ObjectManager $manager): void {
        //accès à un repository depuis l'entitymanager
        $cities = $manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < $number; $i++) {
            $place = new Place();
            $place->setName('place '.$i);
            $place->setAddress(mt_rand(1, 42)." rue XXX");
            $place->setLatitude('latitude '.$i);
            $place->setLongitude('longitude '.$i);
            $place->setCity($this->faker->randomElement($cities));
            $manager->persist($place);
        }
        $manager->flush();
    }

    private function addEvents(int $number, ObjectManager $manager): void {
        //accès à un repository depuis l'entitymanager
        $places = $manager->getRepository(Place::class)->findAll();
        $campuses = $manager->getRepository(Campus::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $statuses = $manager->getRepository(Status::class)->findAll();

        for ($i = 0; $i < $number; $i++) {
            $event = new Event();
            $event->setName('event '.$i);
            $event->setOrganizer($this->faker->randomElement($users));
            $event->setEventStart(new \DateTime());
            $event->setParticipationDeadline(new \DateTime());
            $event->setParticipantLimit(mt_rand(2, 50));
            $event->setDuration(mt_rand(1, 52)*10);
            $event->setDescription('description '.$i);
            $event->setPlace($this->faker->randomElement($places));
            $event->setCampus($this->faker->randomElement($campuses));
            $event->setStatus($this->faker->randomElement($statuses));
            $manager->persist($event);
        }
        $manager->flush();
    }
}
