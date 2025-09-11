<?php

namespace App\Modules\AssetDiscovery\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\AssetDiscovery\Form\AssetFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/assets')]
class AssetController extends AbstractController
{
    #[Route('/', name: 'asset_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $assets = $em->getRepository(Asset::class)->findBy(['user' => $user]);

        return $this->render('asset_discovery/asset/list.html.twig', [
            'assets' => $assets,
        ]);
    }

    #[Route('/create', name: 'asset_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $asset = new Asset();
        $form = $this->createForm(AssetFormType::class, $asset);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $asset->setUser($this->getUser()); // Assign ownership
            $em->persist($asset);
            $em->flush();

            $this->addFlash('success', 'Asset created successfully.');

            return $this->redirectToRoute('asset_list');
        }

        return $this->render('asset_discovery/asset/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'asset_edit')]
    public function edit(Asset $asset, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Check ownership
        if ($asset->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not own this asset.');
        }

        $form = $this->createForm(AssetFormType::class, $asset);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Asset updated successfully.');

            return $this->redirectToRoute('asset_list');
        }

        return $this->render('asset_discovery/asset/edit.html.twig', [
            'form' => $form->createView(),
            'asset' => $asset,
        ]);
    }

    #[Route('/{id}/delete', name: 'asset_delete', methods: ['POST'])]
    public function delete(Asset $asset, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Check ownership
        if ($asset->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not own this asset.');
        }

        if ($this->isCsrfTokenValid('delete-asset'.$asset->getId(), $request->request->get('_token'))) {
            $em->remove($asset);
            $em->flush();

            $this->addFlash('success', 'Asset deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('asset_list');
    }

    #[Route('/{id}', name: 'asset_detail', methods: ['GET'])]
    public function detail(Asset $asset, EntityManagerInterface $em): Response
    {
        // Check ownership
        if ($asset->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You do not own this asset.');
        }

        $vulnerabilities = $em->getRepository(Vulnerability::class)->findBy(['asset' => $asset]);

        return $this->render('asset_discovery/asset/detail.html.twig', [
            'asset' => $asset,
            'vulnerabilities' => $vulnerabilities,
        ]);
    }
}



