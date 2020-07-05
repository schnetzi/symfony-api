<?php

namespace App\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomApiTestCase extends ApiTestCase
{
	protected function createUser(string $email, string $password): User
	{
		$user = new User();
		$user->setEmail($email);
		$user->setUsername(substr($email, 0, strpos($email, '@')));

		$encoded = self::$container->get(UserPasswordEncoderInterface::class)
			->encodePassword($user, $password);

		$user->setPassword($encoded);

		$entityManager = self::$container->get(EntityManagerInterface::class);
		$entityManager->persist($user);
		$entityManager->flush();

		return $user;
	}

	protected function login(Client $client, string $email, string $password)
	{
		$client->request('POST', '/login', [
			'json' => [
				'email' => $email,
				'password' => $password,
			],
		]);

		$this->assertResponseStatusCodeSame(204);
	}

	protected function createUserAndLogin(Client $client, string $email, string $password): User
	{
		$user = $this->createUser($email, $password);
		$this->login($client, $email, $password);

		return $user;
	}
}
