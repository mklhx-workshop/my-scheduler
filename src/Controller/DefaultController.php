<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CalendarRepository $calendarRepository): Response
    {
        // $calendars = $calendarRepository->findBy(['activated' => true]);
        // return $this->render('default/index.html.twig', ['calendars' => $calendars]);
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin():Response
    {
        return $this->redirect('http://localhost:3000');
    }

}
