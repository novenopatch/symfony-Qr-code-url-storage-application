<?php

namespace App\Controller;

use App\Entity\Qrcode;
use App\Form\QrcodeType;
use App\Service\QrcodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/{id?}', name: 'app_home',requirements: ['id' => '\d+'])]
    public function index(?Qrcode $qrcode,Request $request,QrcodeGenerator $qrcodeGenerator): Response
    {
        $form = $this
            ->createForm(QrcodeType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Qrcode $data
             */
            $data = $form->getData();
            $this->entityManager->persist($data);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_home', ['id'=>$data->getId()], Response::HTTP_SEE_OTHER);
        }
        $qrcodeSvg = $qrcodeGenerator->generate($qrcode?->getData());
        return $this->render('home/index.html.twig', [
            'form' => $form,
            'qrcode' => $qrcode,
            'qrcodeSvg' => $qrcodeSvg,
        ]);
    }
}
