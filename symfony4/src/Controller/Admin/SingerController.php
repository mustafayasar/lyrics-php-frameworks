<?php

namespace App\Controller\Admin;

use App\Entity\Singer;
use App\Form\SingerType;
use App\Repository\SingerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/singer")
 */
class SingerController extends AbstractController
{
    /**
     * @Route("/", name="singer_index", methods={"GET"})
     */
    public function index(SingerRepository $singerRepository): Response
    {
        return $this->render('admin/singer/index.html.twig', [
            'singers' => $singerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="singer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $singer = new Singer();
        $form = $this->createForm(SingerType::class, $singer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($singer);
            $entityManager->flush();

            return $this->redirectToRoute('singer_index');
        }

        return $this->render('admin/singer/new.html.twig', [
            'singer' => $singer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="singer_show", methods={"GET"})
     */
    public function show(Singer $singer): Response
    {
        return $this->render('admin/singer/show.html.twig', [
            'singer' => $singer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="singer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Singer $singer): Response
    {
        $form = $this->createForm(SingerType::class, $singer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('singer_index', [
                'id' => $singer->getId(),
            ]);
        }

        return $this->render('admin/singer/edit.html.twig', [
            'singer' => $singer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="singer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Singer $singer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$singer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($singer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('singer_index');
    }
}
