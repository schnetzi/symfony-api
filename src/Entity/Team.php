<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TeamRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *   collectionOperations={"get", "post"},
 *   itemOperations={"get", "patch"},
 *   normalizationContext={
 *     "groups"={"team:read"}
 *   },
 *   denormalizationContext={
 *     "groups"={"team:write"}
 *   }
 * )
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"name": "exact"})
 * @ApiFilter(SearchFilter::class, properties={"group_name": "exact"})
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The team name in German.
     *
     * @ORM\Column(type="string", length=255)
     * @Groups({"team:read", "team:write"})
     */
    private $name;

    /**
     * The group name of the team from A to F.
     *
     * @ORM\Column(type="string", length=2)
     * @Groups({"team:read", "team:write"})
     */
    public $group_name;

    /**
     * The initial position of the team in the group.
     *
     * @ORM\Column(type="smallint")
     * @Groups({"team:read", "team:write"})
     */
    private $position;

    /**
     * The final position the teams ends up.
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"team:read", "team:write"})
     */
    public $final_position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="home_team")
     */
    private $games_home;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="away_team")
     */
    private $games_away;

    public function __construct(string $name = null, string $group_name = null, int $position = null)
    {
        $this->name = $name;
        $this->group_name = $group_name;
        $this->position = $position;
        $this->createdAt = new DateTimeImmutable();
        $this->games_home = new ArrayCollection();
        $this->games_away = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getFinalPosition(): ?int
    {
        return $this->final_position;
    }

    public function setFinalPosition(?int $final_position): self
    {
        $this->final_position = $final_position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection|Game[]
     */
    public function getHomeGames(): Collection
    {
        return $this->games_home;
    }

    public function addHomeGame(Game $homeGame): self
    {
        if (!$this->games_home->contains($homeGame)) {
            $this->games_home[] = $homeGame;
            $homeGame->setHomeTeam($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $homeGame): self
    {
        if ($this->games_home->contains($homeGame)) {
            $this->games_home->removeElement($homeGame);
            // set the owning side to null (unless already changed)
            if ($homeGame->getHomeTeam() === $this) {
                $homeGame->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesAway(): Collection
    {
        return $this->games_away;
    }

    public function addGamesAway(Game $gamesAway): self
    {
        if (!$this->games_away->contains($gamesAway)) {
            $this->games_away[] = $gamesAway;
            $gamesAway->setAwayTeam($this);
        }

        return $this;
    }

    public function removeGamesAway(Game $gamesAway): self
    {
        if ($this->games_away->contains($gamesAway)) {
            $this->games_away->removeElement($gamesAway);
            // set the owning side to null (unless already changed)
            if ($gamesAway->getAwayTeam() === $this) {
                $gamesAway->setAwayTeam(null);
            }
        }

        return $this;
    }
}
