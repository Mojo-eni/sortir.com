<?php

namespace App\Tests;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testRedirectIfNotLoggedIn(): void
    {
        $client = static::createClient();


        $client->request('GET', 'event/create');


        $this->assertResponseRedirects('/login');
    }


}
