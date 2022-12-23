<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-category',
    description: 'Update category.',
    hidden: false,
    aliases: ['app:update-category']
)]
class UpdateCategoryCommand extends Command
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
     * Receive data from a json file, add/update for categories
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $this->projectDir . '/public/data/categories.json';
        $requestData = json_decode(file_get_contents($inputFile));
        $categoryRepository = $this->entityManager->getRepository(Category::class);

        foreach($requestData as $data) {
            $category = $categoryRepository->findBy(['eId' => $data->eId]);

            if (!$category) {
                $category = new Category();
            } elseif (is_array($category)) {
                $category = current($category);
            }
            $this->handleCategory($data, $category);
            $this->entityManager->flush();
        }

        $output->write('Add/Update categories successfully');
        return Command::SUCCESS;
    }

    /**
     * Receive a category object (new/existing), after that will add/update the category
     *
     * @param array $data
     * @param Category $product
     */
    private function handleCategory($data, Category $category)
    {
        $category->setEId($data->eId);
        $category->setTitle($data->title);
        $this->entityManager->persist($category);
    }
}
