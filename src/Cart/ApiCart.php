<?php

namespace App\Cart;

class ApiCart implements CartInterface
{
    public function add(CartItem $item): void
    {
        dd('API CALL: Adding product to remote cart via API - Product ID: ' . $item->getProduct()->getId());
    }

    public function remove(int $productId): void
    {
        dd('API CALL: Removing product from remote cart via API - Product ID: ' . $productId);
    }

    public function getItems(): array
    {
        dd('API CALL: Fetching all items from remote cart via API');
        return [];
    }

    public function clear(): void
    {
        dd('API CALL: Clearing remote cart via API');
    }

    public function getTotalItems(): int
    {
        dd('API CALL: Getting total item count from remote cart via API');
        return 0;
    }

    public function getTotalPrice(): float
    {
        dd('API CALL: Getting total price from remote cart via API');
        return 0.0;
    }
}