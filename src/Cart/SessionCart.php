<?php

namespace App\Cart;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCart implements CartInterface
{
    private const SESSION_KEY = 'cart';
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(CartItem $item): void
    {
        $items = $this->getRawItems();
        $productId = $item->getProduct()->getId();
        $quantity = $item->getQuantity();

        if (isset($items[$productId])) {
            $items[$productId]['quantity'] += $quantity;
        } else {
            $items[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        $this->session->set(self::SESSION_KEY, $items);
    }

    public function remove(int $productId): void
    {
        $items = $this->getRawItems();

        if (isset($items[$productId])) {
            unset($items[$productId]);
            $this->session->set(self::SESSION_KEY, $items);
        }
    }

    public function getItems(): array
    {
        // This method returns CartItem[], but we need the Product repository to hydrate them
        // For now, we'll return the raw items and let the handler deal with hydration
        return $this->getRawItems();
    }

    public function clear(): void
    {
        $this->session->remove(self::SESSION_KEY);
    }

    public function getTotalItems(): int
    {
        $items = $this->getRawItems();
        return array_sum(array_column($items, 'quantity'));
    }

    public function getTotalPrice(): float
    {
        // Requires product data, handled by CartHandler
        return 0.0;
    }

    private function getRawItems(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }
}