<?php

namespace App\Cart;

interface CartInterface
{
    public function add(CartItem $item): void;

    public function remove(int $productId): void;

    /** @return CartItem[] */
    public function getItems(): array;

    public function clear(): void;

    public function getTotalItems(): int;

    public function getTotalPrice(): float;
}