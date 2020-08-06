<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CalendarRepository")
 */
class Calendar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="calendar")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="calendars")
     */
    private $user;


    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $booking): self
    {
        if (!$this->events->contains($booking)) {
            $this->events[] = $booking;
            $booking->setCalendar($this);
        }

        return $this;
    }

    public function removeEvent(Event $booking): self
    {
        if ($this->events->contains($booking)) {
            $this->events->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getCalendar() === $this) {
                $booking->setCalendar(null);
            }
        }

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
