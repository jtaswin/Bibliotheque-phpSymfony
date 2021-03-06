<?php

namespace App\Controller;

use App\Entity\Catalogue;
use App\Form\Catalogue1Type;
use App\Repository\CatalogueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalogue")
 */
class CatalogueController extends AbstractController
{
    /**
     * @Route("/", name="catalogue_index", methods={"GET"})
     */
    public function index(CatalogueRepository $catalogueRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'catalogues' => $catalogueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_livres", methods={"GET"})
     */
    public function catalogueLivres(int $id, CatalogueRepository $catalogueRepository): Response
    {
        $catalogue = $catalogueRepository->find($id);

        return $this->render('catalogue/catalogue.livres.html.twig', [
            'livres' => $catalogue->getLivres(),
        ]);
    }

    /**
     * @Route("/new", name="catalogue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $catalogue = new Catalogue();
        $form = $this->createForm(Catalogue1Type::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catalogue);
            $entityManager->flush();

            return $this->redirectToRoute('catalogue_index');
        }

        return $this->render('catalogue/new.html.twig', [
            'catalogue' => $catalogue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_show", methods={"GET"})
     */
    public function show(Catalogue $catalogue): Response
    {
        return $this->render('catalogue/show.html.twig', [
            'catalogue' => $catalogue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="catalogue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Catalogue $catalogue): Response
    {
        $form = $this->createForm(Catalogue1Type::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catalogue_index');
        }

        return $this->render('catalogue/edit.html.twig', [
            'catalogue' => $catalogue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catalogue_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Catalogue $catalogue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catalogue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catalogue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('catalogue_index');
    }
}
