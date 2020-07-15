<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\TeamRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *   collectionOperations={
 *     "get",
 *     "post"={"security"="is_granted('ROLE_USER')"}
 *   },
 *   itemOperations={
 *     "get",
 *     "put"={"security"="is_granted('ROLE_ADMIN')"},
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *   },
 * )
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"name": "exact"})
 * @ApiFilter(SearchFilter::class, properties={"groupName": "exact"})
 * @ApiFilter(PropertyFilter::class)
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
     * @Groups({"team:read", "team:write", "game:read"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * The group name of the team from A to F.
     *
     * @ORM\Column(type="string", length=2)
     * @Groups({"team:read", "team:write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *    max=1,
     *    maxMessage="Set a group name from A to F"
     * )
     */
    private $groupName;

    /**
     * The initial position of the team in the group.
     *
     * @ORM\Column(type="smallint")
     * @Groups({"team:read", "team:write"})
     * @Assert\NotBlank()
     */
    private $position;

    /**
     * The final position the teams ends up.
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"team:read", "team:write"})
     */
    private $finalPosition;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="homeTeam")
     */
    private $gamesHome;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="awayTeam")
     */
    private $gamesAway;

    public function __construct(string $name = null, string $groupName = null, int $position = null)
    {
        $this->name = $name;
        $this->groupName = $groupName;
        $this->position = $position;
        $this->createdAt = new DateTimeImmutable();
        $this->gamesHome = new ArrayCollection();
        $this->gamesAway = new ArrayCollection();
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
        return $this->groupName;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getFinalPosition(): ?int
    {
        return $this->finalPosition;
    }

    public function setFinalPosition(?int $finalPosition): self
    {
        $this->finalPosition = $finalPosition;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get all games of a team.
     *
     * @Groups({"team:item:get"})
     */
    public function getGames(): Collection
    {
        return new ArrayCollection(
            array_merge($this->gamesHome->toArray(), $this->gamesAway->toArray())
        );
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesHome(): Collection
    {
        return $this->gamesHome;
    }

    public function addGamesHome(Game $gamesHome): self
    {
        if (!$this->gamesHome->contains($gamesHome)) {
            $this->gamesHome[] = $gamesHome;
            $gamesHome->setHomeTeam($this);
        }

        return $this;
    }

    public function removeGamesHome(Game $gamesHome): self
    {
        if ($this->gamesHome->contains($gamesHome)) {
            $this->gamesHome->removeElement($gamesHome);
            // set the owning side to null (unless already changed)
            if ($gamesHome->getHomeTeam() === $this) {
                $gamesHome->setHomeTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGamesAway(): Collection
    {
        return $this->gamesAway;
    }

    public function addGamesAway(Game $gamesAway): self
    {
        if (!$this->gamesAway->contains($gamesAway)) {
            $this->gamesAway[] = $gamesAway;
            $gamesAway->setAwayTeam($this);
        }

        return $this;
    }

    public function removeGamesAway(Game $gamesAway): self
    {
        if ($this->gamesAway->contains($gamesAway)) {
            $this->gamesAway->removeElement($gamesAway);
            // set the owning side to null (unless already changed)
            if ($gamesAway->getAwayTeam() === $this) {
                $gamesAway->setAwayTeam(null);
            }
        }

        return $this;
    }
}
