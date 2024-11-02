<?php

namespace App\Controller;

use App\Entity\Qrcode;
use App\Form\QrcodeType;
use App\Repository\QrcodeRepository;
use App\Security\Voter\QrcodeVoter;
use App\Service\QrcodeGenerator;
use App\Utils\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

#[Route('/qrcode')]
final class QrcodeController extends AbstractController
{
    #[Route(name: 'app_qrcode_index', methods: ['GET'])]
    public function index(QrcodeRepository $qrcodeRepository): Response
    {

        return $this->render('pages/qrcode/index.html.twig', [
            'qrcodes' => $qrcodeRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_qrcode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $qrcode = new Qrcode();
        $form = $this->createForm(QrcodeType::class, $qrcode);
        $form->handleRequest($request);
        $user = $this->getUser();
        //$this->denyAccessUnlessGranted('user_create_shop', $user);
        if ($form->isSubmitted() && $form->isValid()) {
            $qrcode->setAuthor($user);
            $entityManager->persist($qrcode);
            $entityManager->flush();
            return $this->redirectToRoute('app_qrcode_show', ['id'=>$qrcode->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/qrcode/new.html.twig', [
            'qrcode' => $qrcode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qrcode_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Qrcode $qrcode ,QrcodeGenerator $qrcodeGenerator): Response
    {

        $this->denyAccessUnlessGranted(QrcodeVoter::VIEW,$qrcode);
        $qrcodeSvg = $qrcodeGenerator->generate($qrcode?->getData());
        return $this->render('pages/qrcode/show.html.twig', [
            'qrcode' => $qrcode,
            'qrcodeSvg' => $qrcodeSvg,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_qrcode_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Qrcode $qrcode, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted(Constant::ROLE_USER);
        $this->denyAccessUnlessGranted(QrcodeVoter::EDIT,$qrcode);
        $form = $this->createForm(QrcodeType::class, $qrcode);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            foreach ($form->get('tags')->getData() as $tag) {
                $qrcode->addTag($tag);
                $entityManager->persist($qrcode);
            }


            $entityManager->flush();
            return $this->redirectToRoute('app_qrcode_show', ['id'=>$qrcode->getId()], Response::HTTP_SEE_OTHER);
        }
        //dump($qrcode->getTags());
        return $this->render('pages/qrcode/edit.html.twig', [
            'qrcode' => $qrcode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qrcode_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Qrcode $qrcode, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted(Constant::ROLE_USER);
        $this->denyAccessUnlessGranted(QrcodeVoter::EDIT,$qrcode);
        if ($this->isCsrfTokenValid('delete'.$qrcode->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($qrcode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_qrcode_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/ajax/data', name: 'app_qrcode_ajax')]
    public function ajaxData(Request $request, QrcodeRepository $qrcodeRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page - 1) * $limit;
        $qrcodes = $qrcodeRepository->findBy([], null, $limit, $offset);
        $data = [];

        foreach ($qrcodes as $qrcode) {
            $data[]= [
                $qrcode->getData(),
                 $qrcode->getDescription(),
                $qrcode->getCreatedAt()->format('Y-m-d'),
            ];
        }

        return $this->json([
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'total' => $qrcodeRepository->count([]),
        ]);
    }


}
