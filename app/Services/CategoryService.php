<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {

    }

    public function create(array $data): Category
    {
        $category = new Category();

        $category->setName($data['name']);
        $category->setUser($data['user']);

        $this->em->persist($category);
        $this->em->flush();

        return $category;

    }

    public function getAll(): array
    {
        return $this->em->getRepository(Category::class)->findAll();
    }

    public function delete(int $categoryId)
    {
        $category = $this->em->getRepository(Category::class)->find($categoryId);

        $this->em->remove($category);
        $this->em->flush();
    }
    
}
