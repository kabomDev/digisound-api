<?php

namespace App\Entity;

use App\Entity\Artist;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"= {"event:read"}},
 *  denormalizationContext={"groups"={"event"}},
 * )
 * @ApiFilter(RangeFilter::class, properties={"startDate"})
 * @ApiFilter(OrderFilter::class, properties={"startDate"= "ASC"}) 
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event:read","artist:read", "ticket:read", "user:read", "event"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event:read","artist:read", "ticket:read", "user:read","event"}) 
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Assert\Length(min=5, minMessage="le titre doit contenir au minimum 5 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"event:read","artist:read", "ticket:read"}) 
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Assert\Length(min=50, minMessage="la description doit contenir au minimum 50 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event:read","artist:read", "ticket:read", "user:read"})
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Assert\Length(min=2, minMessage="le nom de la ville doit contenir au minimum 2 caractères")
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer", message="Ce type {{ value }} n'est pas valide")  
     * @Assert\NotBlank(message="ce champs ne peut pas rester vide")
     * @Groups({"event:read","artist:read", "ticket:read"})
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     * @Assert\File(
     *  maxSize = "1024k",
     *  mimeTypes = {"image/jpg", "image/png", "image/jpeg"},
     *  mimeTypesMessage = "Veuillez prendre le bon format")
     * @Groups({"event:read","artist:read"})
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read","artist:read", "user:read"})     
     * */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read", "ticket:read", "user:read"})  
     */
    private $endDate;

    /**
     * @ORM\Column(type="float", precision=10, scale=2)
     * @Assert\Type(type="float", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\NotBlank (message="ce champs ne peut pas rester vide")   
     * @Groups({"event:read", "ticket:read", "user:read"})
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, mappedBy="events", cascade={"persist"} , orphanRemoval=true )
     * @Groups({"event:read"})
     */
    private $artists;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="eventName", orphanRemoval=true)
     */
    private $tickets;


    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->addEvent($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeEvent($this);
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function __toString()
    {
        return $this->startDate;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setEventName($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getEventName() === $this) {
                $ticket->setEventName(null);
            }
        }

        return $this;
    }
}
