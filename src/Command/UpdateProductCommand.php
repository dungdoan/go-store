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
    name: 'app:update-product',
    description: 'Update product.',
    hidden: false,
    aliases: ['app:update-product']
)]
class UpdateProductCommand extends Command
{
    /**
     * @param $projectDir
     * @param EntityManagerInterface $entityManager
     */
    public function __construct($projectDir, EntityManagerInterface $entityManager)
    {
        $this->projectDir = $projectDir;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * Receive data from a json file, add/update for products
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $this->projectDir . '/public/data/products.json';
        $requestData = json_decode(file_get_contents($inputFile));
        $productRepository = $this->entityManager->getRepository(Product::class);

        foreach($requestData as $data) {
            $product = $productRepository->findOneBy(['eId' => $data->eId]);
            if (!$product) {
                $product = new Product();
            }
            $this->handleProduct($data, $product);
            $this->entityManager->flush();
        }

        $output->write('Add/Update products successfully');
        return Command::SUCCESS;
    }

    /**
     * Receive a product object (new/existing), after that will add/update the product
     *
     * @param array $data
     * @param Product $product
     */
    private function handleProduct($data, Product $product)
    {
        $product->setEId($data->eId);
        $product->setTitle($data->title);
        $product->setPrice($data->price);
        $product->setCategories(json_encode($data->categoryEId));
        $this->entityManager->persist($product);
    }
}
