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

class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    public function index(Request $request,QrcodeGenerator $qrcodeGenerator,EntityManagerInterface $entityManager, QrcodeRepository $qrcodeRepository): Response
    {
        $qrcode = new Qrcode();
        $form = $this->createForm(QrcodeType::class, $qrcode);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $qrcode->setAuthor($user);
            $entityManager->persist($qrcode);
            $entityManager->flush();
            return $this->redirectToRoute('app_qrcode_show', ['id'=>$qrcode->getId()], Response::HTTP_SEE_OTHER);
        }
        $l_qrcode =$qrcodeRepository->findLatest($user);
        $qrcodeSvg = $qrcodeGenerator->generate($l_qrcode?->getData());
        return $this->render('pages/home/index.html.twig', [//$this->render('pages/home/index.html.twig', [
            'form' => $form,
            'l_qrcode' => $l_qrcode,
            'l_qrcodeSvg' => $qrcodeSvg,
        ]);
    }
}
