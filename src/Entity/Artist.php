<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 * @ApiResource(normalizationContext={"groups"= {"artist:read"} } )
 */
class Artist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"artist:read","event:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Assert\Length(min=2, minMessage="le nom de l'artiste ou groupe doit contenir au minimum 2 caractères")
     * @Groups({"artist:read","event:read", "user:read"})  
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Assert\Length(min=50, minMessage="la description de l'artiste ou groupe doit contenir au minimum 50 caractères")
     * @Groups({"artist:read","event:read", "user:read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="artists", cascade={"persist"})
     * @Groups({"artist:read"})
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
