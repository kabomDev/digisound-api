<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 * @ApiResource(normalizationContext={"groups"= {"ticket:read"}})
 * @ORM\HasLifecycleCallbacks
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ticket:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ticket:read", "user:read"})
     * @Assert\NotBlank(message="Ce champs ne peut pas rester vide")  
     */
    private $eventName;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ticket:read"})
     */
    private $ticketClient;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket:read", "user:read"})
     * @Assert\NotBlank(message="Ce champs ne peut pas rester vide")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"ticket:read", "user:read"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"ticket:read", "user:read"})
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?Event
    {
        return $this->eventName;
    }

    public function setEventName(?Event $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getTicketClient(): ?User
    {
        return $this->ticketClient;
    }

    public function setTicketClient(?User $ticketClient): self
    {
        $this->ticketClient = $ticketClient;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
