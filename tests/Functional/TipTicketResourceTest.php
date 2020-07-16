<?php

namespace App\Tests\Functional;

use App\Entity\TipTicket;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TipTicketResourceTest extends CustomApiTestCase
{
	use ReloadDatabaseTrait;

	public function testCreateTipTicket()
	{
		// always start tests with this line
		$client = self::createClient();

		$client->request('POST', '/api/tip_tickets', [
		  'json' => [],
		]);
		$this->assertResponseStatusCodeSame(401);

		$user = $this->createUserAndLogin($client, 'test@test.com', 'asdf');

		$client->request('POST', '/api/tip_tickets', [
		  'json' => [
				'user' => '/api/users/'.$user->getId(),
			],
		]);
		$this->assertResponseStatusCodeSame(201);
	}

	public function testUpdateTipTicket()
	{
		$client = self::createClient();
		$user1 = $this->createUser('user1@test.com', 'asdf');
		$user2 = $this->createUser('user2@test.com', 'asdf');

		$tipTicket = new TipTicket();
		$tipTicket->setUser($user1);
		$tipTicket->setIsPaid(true);

		$entityManager = $this->getEntityManager();
		$entityManager->persist($tipTicket);
		$entityManager->flush();

		$this->login($client, 'user2@test.com', 'asdf');
		$client->request('PUT', '/api/tip_tickets/'.$tipTicket->getId());
		$this->assertResponseStatusCodeSame(403);

		$this->login($client, 'user1@test.com', 'asdf');
		$client->request('PUT', '/api/tip_tickets/'.$tipTicket->getId(), [
			'json' => ['isPaid' => true],
		]);
		$this->assertResponseStatusCodeSame(200);
	}

	public function testGetTipTicketCollection() {
	    $client = self::createClient();
	    $user = $this->createUserAndLogin($client, 'user1@test.com', 'asdf');

	    $tipTicket1 = new TipTicket();
        $tipTicket1->setUser($user);
        $tipTicket1->setIsPaid(true);

        $tipTicket2 = new TipTicket();
        $tipTicket2->setUser($user);

        $tipTicket3 = new TipTicket();
        $tipTicket3->setUser($user);
        $tipTicket3->setIsPaid(true);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($tipTicket1);
        $entityManager->persist($tipTicket2);
        $entityManager->persist($tipTicket3);
        $entityManager->flush();

        $client->request('GET', '/api/tip_tickets');
        $this->assertJsonContains(['hydra:totalItems' => 2]);
    }

    public function testGetTipTicketItem() {
        $client = self::createClient();
        $user = $this->createUserAndLogin($client, 'user1@test.com', 'asdf');

        $tipTicket1 = new TipTicket();
        $tipTicket1->setUser($user);
        $tipTicket1->setIsPaid(false);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($tipTicket1);
        $entityManager->flush();

        $client->request('GET', '/api/tip_tickets/'. $tipTicket1->getId());
        $this->assertResponseStatusCodeSame(404);
//        $this->assertJsonContains(['hydra:totalItems' => 1]);
    }
}
