<?php

namespace App\Controller;

use App\Entity\Qrcode;
use App\Form\QrcodeType;
use App\Repository\QrcodeRepository;
use App\Service\QrcodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/qrcode')]
final class QrcodeController extends AbstractController
{
    #[Route(name: 'app_qrcode_index', methods: ['GET'])]
    public function index(QrcodeRepository $qrcodeRepository): Response
    {
        return $this->render('qrcode/index.html.twig', [
            'qrcodes' => $qrcodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_qrcode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $qrcode = new Qrcode();
        $form = $this->createForm(QrcodeType::class, $qrcode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($qrcode);
            $entityManager->flush();
            return $this->redirectToRoute('app_qrcode_show', ['id'=>$qrcode->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('qrcode/new.html.twig', [
            'qrcode' => $qrcode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qrcode_show', methods: ['GET'])]
    public function show(Qrcode $qrcode ,QrcodeGenerator $qrcodeGenerator): Response
    {
        $qrcodeSvg = $qrcodeGenerator->generate($qrcode?->getData());
        return $this->render('qrcode/show.html.twig', [
            'qrcode' => $qrcode,
            'qrcodeSvg' => $qrcodeSvg,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_qrcode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Qrcode $qrcode, EntityManagerInterface $entityManager): Response
    {
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
        dump($qrcode->getTags());
        return $this->render('qrcode/edit.html.twig', [
            'qrcode' => $qrcode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qrcode_delete', methods: ['POST'])]
    public function delete(Request $request, Qrcode $qrcode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$qrcode->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($qrcode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_qrcode_index', [], Response::HTTP_SEE_OTHER);
    }
}
