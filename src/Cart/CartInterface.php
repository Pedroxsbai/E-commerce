<?php

namespace App\Cart;

interface CartInterface
{
    /**
     * Add a product to the cart with a specific quantity.
     */
    public function add(CartItem $item): void;

    /**
     * Remove a product from the cart.
     */
    public function remove(int $productId): void;

    /**
     * Get all items in the cart.
     *
     * @return CartItem[]
     */
    public function getItems(): array;

    /**
     * Clear the cart.
     */
    public function clear(): void;

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalItems(): int;

    /**
     * Get the total price of all items in the cart.
     */
    public function getTotalPrice(): float;
}