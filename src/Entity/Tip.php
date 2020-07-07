<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TipRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *   collectionOperations={
 *     "get"={"security"="is_granted('ROLE_ADMIN')"},
 *     "post"={"security"="is_granted('ROLE_USER')"}
 *   },
 *	itemOperations={
 *		"get"={"security"="is_granted('ROLE_USER')"},
 *		"put"={"security"="is_granted('TIP_EDIT', object)"},
 * 		"delete"={"security"="is_granted('ROLE_USER') and object.getTipTicket.getUser() == user"}
 *	},
 * 	 normalizationContext={
 *     "groups"={"tip:read"}
 *   },
 * 	 denormalizationContext={
 *     "groups"={"tip:write"}
 *   }
 * )
 * @ORM\Entity(repositoryClass=TipRepository::class)
 * @ApiFilter(
 *  SearchFilter::class,
 *  properties={
 *    "tipTicket": "exact",
 *  }
 * )
 */
class Tip
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity=TipTicket::class, inversedBy="tips")
	 * @ORM\JoinColumn(nullable=false)
	 * @Groups({"tip:read", "tip:write"})
	 * @Assert\NotBlank()
	 */
	private $tipTicket;

	/**
	 * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="tips")
	 * @ORM\JoinColumn(nullable=false)
	 * @Groups({"tip:read", "tip:write"})
	 * @Assert\NotBlank()
	 */
	private $game;

	/**
	 * @ORM\Column(type="smallint")
	 * @Groups({"tip:read", "tip:write"})
	 * @Assert\NotBlank()
	 */
	private $homeTeamScore;

	/**
	 * @ORM\Column(type="smallint")
	 * @Groups({"tip:read", "tip:write"})
	 * @Assert\NotBlank()
	 */
	private $awayTeamScore;

	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"tip:read"})
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"tip:read"})
	 */
	private $updatedAt;

	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTipTicket(): ?TipTicket
	{
		return $this->tipTicket;
	}

	public function setTipTicket(?TipTicket $tipTicket): self
	{
		$this->tipTicket = $tipTicket;

		return $this;
	}

	public function getGame(): ?Game
	{
		return $this->game;
	}

	public function setGame(?Game $game): self
	{
		$this->game = $game;

		return $this;
	}

	public function getHomeTeamScore(): ?int
	{
		return $this->homeTeamScore;
	}

	public function setHomeTeamScore(int $homeTeamScore): self
	{
		$this->homeTeamScore = $homeTeamScore;

		return $this;
	}

	public function getAwayTeamScore(): ?int
	{
		return $this->awayTeamScore;
	}

	public function setAwayTeamScore(int $awayTeamScore): self
	{
		$this->awayTeamScore = $awayTeamScore;

		return $this;
	}

	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): ?\DateTimeInterface
	{
		return $this->updatedAt;
	}

	/**
	 * @ORM\PreUpdate()
	 */
	public function preUpdate()
	{
		$this->updatedAt = new \DateTime();
	}
}
