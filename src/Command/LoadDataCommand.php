<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:load-data',
    description: 'Load initial test data (categories & products)'
)]
class LoadDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Chargement des donnees...');

        $categoriesData = [
            ['name' => 'Electronics', 'description' => 'Headphones, speakers, gadgets', 'badgeClass' => 'bg-primary'],
            ['name' => 'Fashion', 'description' => 'Clothing, accessories', 'badgeClass' => 'bg-warning'],
            ['name' => 'Home & Garden', 'description' => 'Furniture, decor', 'badgeClass' => 'bg-success'],
            ['name' => 'Sports', 'description' => 'Workout gear, yoga mats', 'badgeClass' => 'bg-info'],
            ['name' => 'Books', 'description' => 'Fiction, non-fiction', 'badgeClass' => 'bg-danger'],
            ['name' => 'Beauty & Health', 'description' => 'Skincare, wellness', 'badgeClass' => 'bg-secondary'],
            ['name' => 'Toys & Games', 'description' => 'Fun for kids', 'badgeClass' => 'bg-primary'],
            ['name' => 'Automotive', 'description' => 'Car accessories', 'badgeClass' => 'bg-dark'],
            ['name' => 'Pet Supplies', 'description' => 'Food, toys for pets', 'badgeClass' => 'bg-warning'],
        ];

        $categoryEntities = [];
        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setDescription($data['description']);
            $category->setBadgeClass($data['badgeClass']);
            $this->entityManager->persist($category);
            $categoryEntities[$data['name']] = $category;
        }
        $this->entityManager->flush();

        $productsData = [
            ['name' => 'Wireless Headphones', 'price' => 79.99, 'sku' => 'WHP-001', 'category' => 'Electronics'],
            ['name' => 'Bluetooth Speaker', 'price' => 59.99, 'sku' => 'BSP-002', 'category' => 'Electronics'],
            ['name' => 'Classic Leather Jacket', 'price' => 149.99, 'sku' => 'CLJ-003', 'category' => 'Fashion'],
            ['name' => 'Running Shoes', 'price' => 89.99, 'sku' => 'RSR-004', 'category' => 'Fashion'],
            ['name' => 'Smart Plant Sensor', 'price' => 34.99, 'sku' => 'SPS-005', 'category' => 'Home & Garden'],
            ['name' => 'Yoga Mat Premium', 'price' => 29.99, 'sku' => 'YMP-007', 'category' => 'Sports'],
            ['name' => 'Web Development Guide', 'price' => 24.99, 'sku' => 'WDG-009', 'category' => 'Books'],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription('A great ' . $data['name']);
            $product->setPrice($data['price']);
            $product->setSku($data['sku']);
            $product->setStockStatus('In Stock');
            $product->setCategory($categoryEntities[$data['category']]);
            $this->entityManager->persist($product);
        }
        $this->entityManager->flush();

        $output->writeln('Done!');
        return Command::SUCCESS;
    }
}