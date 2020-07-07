<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TipTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *		"put"={"security"="is_granted('ROLE_USER') and object.getUser() == user"},
 * 		"delete"={"security"="is_granted('ROLE_USER')"}
 *	},
 *	normalizationContext={
 *		"groups"={"tip_ticket:read"}
 *	},
 *	denormalizationContext={
 *		"groups"={"tip_ticket:write"}
 *	}
 * )
 * @ORM\Entity(repositoryClass=TipTicketRepository::class)
 */
class TipTicket
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tipTickets")
	 * @ORM\JoinColumn(nullable=false)
	 * @Groups({"tip_ticket:read", "tip_ticket:write"})
	 * @Assert\NotBlank()
	 */
	private $user;

	/**
	 * @ORM\Column(type="boolean")
	 * @Groups({"tip_ticket:read", "tip_ticket:write"})
	 */
	private $isPaid = false;

	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"tip_ticket:read"})
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"tip_ticket:read"})
	 */
	private $updatedAt;

	/**
	 * @ORM\OneToMany(targetEntity=Tip::class, mappedBy="tipTicket", orphanRemoval=true)
	 */
	private $tips;

	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
		$this->tips = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getIsPaid(): ?bool
	{
		return $this->isPaid;
	}

	public function setIsPaid(bool $isPaid): self
	{
		$this->isPaid = $isPaid;

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

	/**
	 * @return Collection|Tip[]
	 */
	public function getTips(): Collection
	{
		return $this->tips;
	}

	public function addTip(Tip $tip): self
	{
		if (!$this->tips->contains($tip)) {
			$this->tips[] = $tip;
			$tip->setTipTicket($this);
		}

		return $this;
	}

	public function removeTip(Tip $tip): self
	{
		if ($this->tips->contains($tip)) {
			$this->tips->removeElement($tip);
			// set the owning side to null (unless already changed)
			if ($tip->getTipTicket() === $this) {
				$tip->setTipTicket(null);
			}
		}

		return $this;
	}
}
