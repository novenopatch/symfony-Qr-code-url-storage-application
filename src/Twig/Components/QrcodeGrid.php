<?php

namespace App\Twig\Components;

use App\Repository\QrcodeRepository;
use App\Service\QrcodeGenerator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class QrcodeGrid
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    private const PER_PAGE = 5;
    #[LiveProp]
    public int $page = 1;

    public function __construct(private readonly QrcodeRepository $qrcodeRepository,private readonly Security $security)
    {
    }
    public function getItems(): array
    {
        return $this->qrcodeRepository->findPaginated($this->page, self::PER_PAGE,$this->security->getUser())['items'];
    }
    #[ExposeInTemplate('per_page')]
    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    public function hasMore(): bool
    {
        return $this->qrcodeRepository->countAll($this->security->getUser()) > ($this->page * self::PER_PAGE);
    }

    #[LiveAction]
    public function more(): void
    {
        ++$this->page;
    }
}
