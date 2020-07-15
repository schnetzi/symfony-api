<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $teams = [];
        $team = new Team('Türkei', 'A', '1');
        $teams[] = $team;
        $team = new Team('Italien', 'A', '2');
        $teams[] = $team;
        $team = new Team('Wales', 'A', '3');
        $teams[] = $team;
        $team = new Team('Schweiz', 'A', '4');
        $teams[] = $team;
        foreach ($teams as $team) {
            $manager->persist($team);
        }

        $manager->flush();
    }
}
