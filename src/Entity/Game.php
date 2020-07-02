<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\GameRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *   collectionOperations={
 *     "get",
 *     "post"={"security"="is_granted('ROLE_ADMIN')"}
 *   },
 *   itemOperations={
 *     "get",
 *     "patch"={"security"="is_granted('ROLE_ADMIN')"},
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *   },
 *   normalizationContext={
 *     "groups"={"game:read"}
 *   },
 *   denormalizationContext={
 *     "groups"={"game:write"}
 *   }
 * )
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ApiFilter(
 * 	SearchFilter::class,
 * 	properties={
 *    "homeTeam": "exact",
 *    "awayTeam": "exact",
 *    "homeTeam.name": "partial",
 *    "awayTeam.name": "partial"
 * 	}
 * )
 * @ApiFilter(PropertyFilter::class)
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
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="gamesHome")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"game:read", "game:write", "team:read"})
     * @Assert\NotBlank()
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="gamesAway")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"game:read", "game:write", "team:read"})
     * @Assert\NotBlank()
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"game:read", "game:write", "team:read"})
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"game:read", "game:write", "team:read"})
     * @Assert\NotBlank()
     */
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

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
