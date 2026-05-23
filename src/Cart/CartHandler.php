<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;

class CartHandler
{
    private CartInterface $cartStrategy;
    private ProductRepository $productRepository;

    public function __construct(
        #[Autowire(service: 'App\Cart\SessionCart')] CartInterface $cartStrategy,
        ProductRepository $productRepository
    ) {
        $this->cartStrategy = $cartStrategy;
        $this->productRepository = $productRepository;
    }

    public function addProduct(Product $product, int $quantity = 1): void
    {
        $item = new CartItem($product, $quantity);
        $this->cartStrategy->add($item);
    }

    public function removeProduct(int $productId): void
    {
        $this->cartStrategy->remove($productId);
    }

    public function getCartItems(): array
    {
        $rawItems = $this->cartStrategy->getItems();
        $items = [];

        foreach ($rawItems as $rawItem) {
            $product = $this->productRepository->find($rawItem['product_id']);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $rawItem['quantity'],
                ];
            }
        }

        return $items;
    }

    public function clear(): void
    {
        $this->cartStrategy->clear();
    }

    public function getTotalItems(): int
    {
        return $this->cartStrategy->getTotalItems();
    }

    public function getTotalPrice(): float
    {
        $items = $this->getCartItems();
        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }
}