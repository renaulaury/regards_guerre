<?php
namespace App\Twig;

use App\Service\BasketService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BasketExtension extends AbstractExtension
{
    public function __construct(
        private readonly BasketService $basketService
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('productCounter', [$this->basketService, 'basketCount']),
        ];
    }
}

