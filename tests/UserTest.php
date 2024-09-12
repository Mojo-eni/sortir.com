<?php

namespace App\Tests;

use App\Entity\Event;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testUserInitialValues(): void
    {
        $user = new User();

        $this->assertNull($user->getId());
        $this->assertNull($user->getFirstName());
        $this->assertNull($user->getLastName());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertNull($user->getPhone());
        $this->assertNull($user->getCampus());
        $this->assertCount(0, $user->getEvents());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testUserSettersAndGetters(): void
    {
        $user = new User();
        $user->setPseudo('john_doe');
        $user->setEmail('john.doe@example.com');
        $user->setRoles(['ROLE_USER']);

        $this->assertEquals('john_doe', $user->getPseudo());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }




}