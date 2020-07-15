<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
	use ReloadDatabaseTrait;

	public function testCreateUser()
	{
		// always start tests with this line
		$client = self::createClient();

		$client->request(
			'POST',
			'/api/users',
			[
				'json' => [
					'email' => 'test@test.com',
					'password' => 'asdf',
					'username' => 'test',
				],
			]
		);
		$this->assertResponseStatusCodeSame(201);

		$this->login($client, 'test@test.com', 'asdf');
	}

	public function testUpdateUser()
	{
		// always start tests with this line
		$client = self::createClient();

		$user = $this->createUserAndLogin($client, 'test@test.com', 'asdf');

		$client->request(
			'PUT',
			'/api/users/'.$user->getId(),
			[
				'json' => [
					'username' => 'test1',
					'roles' => ['ROLE_ADMIN'],
				],
			]
		);

		$this->assertResponseIsSuccessful();
		$this->assertJsonContains(
			[
				'username' => 'test1',
			]
		);

		$entityManager = $this->getEntityManager();
		/** @var User $user */
		$user = $entityManager->getRepository(User::class)->find($user->getId());
		$this->assertEquals(['ROLE_USER'], $user->getRoles());
	}

	public function testGetUser()
	{
		// always start tests with this line
		$client = self::createClient();

		$user = $this->createUser('test@test.com', 'asdf');
		$user->setPhoneNumber('06601234567890');
		$this->createUserAndLogin($client, 'mein@anderer.com', 'testeer');
		$entityManager = $this->getEntityManager();
		$entityManager->flush();

		$client->request('GET', '/api/users/'.$user->getId());
		$this->assertJsonContains(
			[
				'username' => 'test',
			]
		);

		$data = $client->getResponse()->toArray();
		$this->assertArrayNotHasKey('phoneNumber', $data);

		// refresh the user & elevate to admin
		$user = $entityManager->getRepository(User::class)->find($user->getId());
		$user->setRoles(['ROLE_ADMIN']);
		$entityManager->flush();
		// new login that symfony understands the roles changed
		$this->login($client, 'test@test.com', 'asdf');

		$client->request('GET', '/api/users/'.$user->getId());
		$this->assertJsonContains(
			[
				'phoneNumber' => '06601234567890',
			]
		);
	}
}
