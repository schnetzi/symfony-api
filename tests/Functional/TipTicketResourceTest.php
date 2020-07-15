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

		$entityManager = $this->getEntityManager();
		$entityManager->persist($tipTicket);
		$entityManager->flush();

		$this->login($client, 'user2@test.com', 'asdf');
		$client->request('PUT', '/api/tip_tickets/'.$tipTicket->getId(), [
			'json' => ['isPaid' => true],
		]);
		$this->assertResponseStatusCodeSame(403);

		$this->login($client, 'user1@test.com', 'asdf');
		$client->request('PUT', '/api/tip_tickets/'.$tipTicket->getId(), [
			'json' => ['isPaid' => true],
		]);
		$this->assertResponseStatusCodeSame(200);
	}
}