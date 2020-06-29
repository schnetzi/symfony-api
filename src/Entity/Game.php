<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
    private $home_team_id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="games_away")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"game:read", "game:write"})
     */
    private $away_team_id;

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

    public function getHomeTeamId(): ?Team
    {
        return $this->home_team_id;
    }

    public function setHomeTeamId(?Team $home_team_id): self
    {
        $this->home_team_id = $home_team_id;

        return $this;
    }

    public function getAwayTeamId(): ?Team
    {
        return $this->away_team_id;
    }

    public function setAwayTeamId(?Team $away_team_id): self
    {
        $this->away_team_id = $away_team_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Show the human readable time diff in German.
     *
     * @Groups({"game:read", "game:write"})
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
