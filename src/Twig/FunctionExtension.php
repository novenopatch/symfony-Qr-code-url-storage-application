<?php

namespace App\Twig;

use App\Entity\Qrcode;
use App\Service\QrcodeGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionExtension extends AbstractExtension
{
    public function __construct(private readonly QrcodeGenerator $qrcodeGenerator){}
    public function getFunctions():array
    {
        return[
            new TwigFunction('getCodeSvg', [$this, 'getCodeSvg']),

        ];
    }
    public function getCodeSvg(Qrcode $entity=null): ?string
    {
        if($entity === null) {
            return null;}
        return $this->qrcodeGenerator->generate($entity?->getData());



    }
}