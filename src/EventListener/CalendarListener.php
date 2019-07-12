<?php

namespace App\EventListener;

use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use App\Repository\CalendarRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarListener
{
    private $calendarRepository;
    private $router;

    public function __construct(CalendarRepository $calendarRepository,UrlGeneratorInterface $router) {
        $this->calendarRepository = $calendarRepository;
        $this->router = $router;
    }

    public function load(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // You may want to make a custom query to fill the calendar

        $calendar->addEvent(new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesdays this week')
        ));

        // If the end date is null or not defined, it creates a all day event
        $calendar->addEvent(new Event(
            'All day event',
            new \DateTime('Friday this week')
        ));
    }
}