<?php

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TeamResourceTest extends CustomApiTestCase
{
	use ReloadDatabaseTrait;

	public function testCreateTeam()
	{
		$client = self::createClient();

		$client->request('POST', '/api/teams', [
		  'json' => [],
		]);
		$this->assertResponseStatusCodeSame(401);

		$this->createUserAndLogin($client, 'test@test.com', 'asdf');

		$client->request('POST', '/api/teams', [
		  'json' => [
				'name' => 'test',
				'groupName' => 'A',
				'position' => 1,
			],
		]);
		$this->assertResponseStatusCodeSame(201);
	}
}
