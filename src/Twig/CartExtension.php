<?php
namespace App\Twig;

use App\Service\CartService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    // private $cartService;

    // public function __construct(CartService $cartService)
    // {
    //     $this->cartService = $cartService;
    // }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('productCounter', [$this->cartService, 'cartCount']),
    //     ];
    // }
}


