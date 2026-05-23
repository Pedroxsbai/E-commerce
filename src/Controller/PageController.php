<?php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('pages/home.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/products', name: 'app_products')]
    public function products(ProductRepository $productRepository): Response
    {
        $allProducts = $productRepository->findAll();
        return $this->render('pages/products.html.twig', [
            'products' => $allProducts,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_details', requirements: ['id' => '\d+'])]
    public function productDetails(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        return $this->render('pages/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function cart(CartHandler $cartHandler): Response
    {
        $items = $cartHandler->getCartItems();
        $totalPrice = $cartHandler->getTotalPrice();
        $totalItems = $cartHandler->getTotalItems();

        return $this->render('pages/cart.html.twig', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'totalItems' => $totalItems,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function addToCart(int $id, CartHandler $cartHandler, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        
        $quantity = max(1, (int) $request->request->get('quantity', 1));
        $cartHandler->addProduct($product, $quantity);

        $this->addFlash('success', 'Product added to cart!');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function removeFromCart(int $id, CartHandler $cartHandler): Response
    {
        $cartHandler->removeProduct($id);
        
        $this->addFlash('success', 'Product removed from cart!');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/categories/{category}', name: 'app_products_by_category')]
    public function productsByCategory(string $category, CategoryRepository $categoryRepository): Response
    {
        $categoryEntity = $categoryRepository->findOneBy(['name' => $category]);
        if (!$categoryEntity) {
            throw $this->createNotFoundException('Category not found');
        }
        $products = $categoryEntity->getProducts();
        return $this->render('pages/products_by_category.html.twig', [
            'category' => $categoryEntity,
            'products' => $products,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('pages/profile.html.twig');
    }
}