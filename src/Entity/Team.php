<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
    private $group_name;

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
    private $final_position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="home_team_id")
     */
    private $games_home;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="away_team_id")
     */
    private $games_away;

    public function __construct()
    {
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
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
            $homeGame->setHomeTeamId($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $homeGame): self
    {
        if ($this->games_home->contains($homeGame)) {
            $this->games_home->removeElement($homeGame);
            // set the owning side to null (unless already changed)
            if ($homeGame->getHomeTeamId() === $this) {
                $homeGame->setHomeTeamId(null);
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
            $gamesAway->setAwayTeamId($this);
        }

        return $this;
    }

    public function removeGamesAway(Game $gamesAway): self
    {
        if ($this->games_away->contains($gamesAway)) {
            $this->games_away->removeElement($gamesAway);
            // set the owning side to null (unless already changed)
            if ($gamesAway->getAwayTeamId() === $this) {
                $gamesAway->setAwayTeamId(null);
            }
        }

        return $this;
    }
}
