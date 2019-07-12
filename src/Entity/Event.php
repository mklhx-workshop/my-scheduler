<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Thing", inversedBy="event")
     */
    private $thing;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Calendar", inversedBy="events")
     */
    private $calendar;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    /**
     * @param \DateTimeInterface $start
     * @return Event
     */
    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @param \DateTimeInterface $end
     * @return Event
     */
    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return Event
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Thing|null
     */
    public function getThing(): ?Thing
    {
        return $this->thing;
    }

    /**
     * @param Thing|null $thing
     * @return Event
     */
    public function setThing(?Thing $thing): self
    {
        $this->thing = $thing;

        return $this;
    }

    /**
     * @return Calendar|null
     */
    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar|null $calendar
     * @return Event
     */
    public function setCalendar(?Calendar $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }
}
