<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * 	 collectionOperations={"get", "post"},
 *   itemOperations={"get"},
 * 	 normalizationContext={
 *     "groups"={"game:read"}
 *   },
 * 	 denormalizationContext={
 *     "groups"={"game:write"}
 *   }
 * )
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ApiFilter(
 * 	SearchFilter::class,
 * 	properties={
 * 		"home_team": "exact",
 * 		"away_team": "exact"
 * 	}
 * )
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="games_home")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"game:read", "game:write"})
     */
    public $home_team;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="games_away")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"game:read", "game:write"})
     */
    public $away_team;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"game:read", "game:write"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"game:read", "game:write"})
     */
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->home_team;
    }

    public function setHomeTeam(?Team $home_team): self
    {
        $this->home_team = $home_team;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->away_team;
    }

    public function setAwayTeam(?Team $away_team): self
    {
        $this->away_team = $away_team;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Show the human readable time diff in German.
     *
     * @Groups({"game:read"})
     */
    public function getDateDiff(): string
    {
        Carbon::setLocale('de');

        return Carbon::instance($this->getDate())->diffForHumans();
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
