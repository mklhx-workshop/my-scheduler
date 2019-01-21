<?php

namespace App\Controller;

use App\Entity\Thing;
use App\Form\ThingType;
use App\Repository\ThingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/thing")
 */
class ThingController extends AbstractController
{
    /**
     * @Route("/", name="thing_index", methods="GET")
     */
    public function index(ThingRepository $thingRepository): Response
    {
        return $this->render('thing/index.html.twig', ['things' => $thingRepository->findAll()]);
    }

    /**
     * @Route("/new", name="thing_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $thing = new Thing();
        $form = $this->createForm(ThingType::class, $thing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($thing);
            $em->flush();

            return $this->redirectToRoute('thing_index');
        }

        return $this->render('thing/new.html.twig', [
            'thing' => $thing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="thing_show", methods="GET")
     */
    public function show(Thing $thing): Response
    {
        return $this->render('thing/show.html.twig', ['thing' => $thing]);
    }

    /**
     * @Route("/{id}/edit", name="thing_edit", methods="GET|POST")
     */
    public function edit(Request $request, Thing $thing): Response
    {
        $form = $this->createForm(ThingType::class, $thing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('thing_index', ['id' => $thing->getId()]);
        }

        return $this->render('thing/edit.html.twig', [
            'thing' => $thing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="thing_delete", methods="DELETE")
     */
    public function delete(Request $request, Thing $thing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thing->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($thing);
            $em->flush();
        }

        return $this->redirectToRoute('thing_index');
    }
}
