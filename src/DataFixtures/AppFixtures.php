<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Status;
use App\Entity\User;
use DateInterval;
use DateTime;
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
        $this->addCities(5, $manager);
        $this->addCampuses($manager);
        $this->addUsers(5, $manager);
        $this->addStatuses($manager);
        $this->addPlaces(10, $manager);
        $this->addEvents(10, $manager);
    }

    private function addCities(int $number, ObjectManager $manager): void {
        $city1 = new City();
        $city1->setName('Saint-Herblain');
        $city1->setZipcode('44800');
        $manager->persist($city1);
        $city2 = new City();
        $city2->setName('La-Roche-sur-Yon');
        $city2->setZipcode('85000');
        $manager->persist($city2);
        $city3 = new City();
        $city3->setName('Chartres-de-Bretagne');
        $city3->setZipcode('35131');
        $manager->persist($city3);
        $city4 = new City();
        $city4->setName('Rennes');
        $city4->setZipcode('35000');
        $manager->persist($city4);
        $city5 = new City();
        $city5->setName('Guichen');
        $city5->setZipcode('35580');
        $manager->persist($city5);

//        for ($i = 0; $i < $number; $i++) {
//            $city = new City();
//            $city->setName('city '.$i);
//            $city->setZipcode(mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9));
//            $manager->persist($city);
//        }

        $manager->flush();
    }

    private function addStatuses(ObjectManager $manager): void {
        $status1 = new Status();
        $status1->setName('Créée');
        $manager->persist($status1);

        $status2 = new Status();
        $status2->setName('Ouverte');
        $manager->persist($status2);

        $status3 = new Status();
        $status3->setName('Clôturée');
        $manager->persist($status3);

        $status4 = new Status();
        $status4->setName('En cours');
        $manager->persist($status4);

        $status5 = new Status();
        $status5->setName('Passée');
        $manager->persist($status5);

        $status6 = new Status();
        $status6->setName('Annulée');
        $manager->persist($status6);

//        // create 5 status
//        for ($i = 0; $i < 6; $i++) {
//            $status = new Status();
//            $status->setName('status '.$i);
//            $manager->persist($status);
//        }

        $manager->flush();
    }

    private function addCampuses(ObjectManager $manager): void {
        // accès à un repository depuis l'entitymanager
        $cities = $manager->getRepository(City::class)->findAll();

        $campus1 = new Campus();
        $campus1->setName("CDB");
        $campus1->setCity($manager->getRepository(City::class)->findOneBy(["name" => "Chartres-de-Bretagne"]));
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setName("La Roche");
        $campus2->setCity($manager->getRepository(City::class)->findOneBy(["name" => "La-Roche-sur-Yon"]));
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setName("Saint-Herblain");
        $campus3->setCity($manager->getRepository(City::class)->findOneBy(["name" => "Saint-Herblain"]));
        $manager->persist($campus3);


        // accès à un repository depuis l'entitymanager
//        $cities = $manager->getRepository(City::class)->findAll();
//        for ($i = 0; $i < 4; $i++) {
//            $campus = new Campus();
//            $campus->setName('campus '.$i);
//            $campus->setCity($this->faker->randomElement($cities));
//            $manager->persist($campus);
//        }

        $manager->flush();
    }

    private function addUsers(int $number, ObjectManager $manager): void
    {
        $campuses = $manager->getRepository(Campus::class)->findAll();
        for ($i = 0; $i < $number; $i++) {
            $user = new User();
            $user->setPseudo("user ".$i)
                ->setLastName($this->faker->lastName())
                ->setFirstName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setActif(true)
                ->setRoles(['ROLE_USER'])
                ->setCampus($this->faker->randomElement($campuses))
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
            $place->setName('Nom de lieu n° '.$i);
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
        $now = new DateTime();
        $twoWeeksAgo = $now->sub(new DateInterval('P15D'));


        for ($i = 0; $i < $number; $i++) {
            $event = new Event();
            $event->setName('nom de sortie n° '.$i);
            $event->setOrganizer($this->faker->randomElement($users));
            $event->setEventStart(new DateTime());
            $event->setParticipationDeadline(new DateTime());
            $event->setParticipantLimit(mt_rand(2, 50));
            $event->setDuration(mt_rand(1, 52)*10);
            $event->setDescription('description '.$i);
            $event->setPlace($this->faker->randomElement($places));
            $event->setCampus($this->faker->randomElement($campuses));
            $event->setStatus($this->faker->randomElement($statuses));
            $event->addParticipant($this->faker->randomElement($users));
            $manager->persist($event);
        }

        $event2 = new Event();
        $event2->setName('2 weeks ago ');
        $event2->setOrganizer($this->faker->randomElement($users));
        $event2->setEventStart($twoWeeksAgo);
        $event2->setParticipationDeadline($twoWeeksAgo);
        $event2->setParticipantLimit(mt_rand(2, 50));
        $event2->setDuration(mt_rand(1, 52)*10);
        $event2->setDescription('description '.$i);
        $event2->setPlace($this->faker->randomElement($places));
        $event2->setCampus($this->faker->randomElement($campuses));
        $event2->setStatus($this->faker->randomElement($statuses));
        $event2->addParticipant($this->faker->randomElement($users));
        $manager->persist($event2);

        $event3 = new Event();
        $event3->setName('Very long ago ');
        $event3->setOrganizer($this->faker->randomElement($users));
        $event3->setEventStart(new DateTime('2022-05-09'));
        $event3->setParticipationDeadline(new DateTime('2022-05-09'));
        $event3->setParticipantLimit(mt_rand(2, 50));
        $event3->setDuration(mt_rand(1, 52)*10);
        $event3->setDescription('description '.$i);
        $event3->setPlace($this->faker->randomElement($places));
        $event3->setCampus($this->faker->randomElement($campuses));
        $event3->setStatus($this->faker->randomElement($statuses));
        $event3->addParticipant($this->faker->randomElement($users));
        $manager->persist($event3);

        $manager->flush();
    }
}
