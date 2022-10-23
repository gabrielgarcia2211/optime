<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private $validator;
    private $manager;

    public function __construct( EntityManagerInterface $manager, ManagerRegistry $registry,  ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        parent::__construct($registry, Category::class);
    }


    public function allCategories()
    {
        $categoriesList = $this->findAll();
        return $categoriesList;
    }

    public function saveCategory($name, $activate)
    {
        $newCategory = new Category();


        $newCategory->setName($name)->setActive($activate);

        $errors = $this->validator->validate($newCategory);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return ['status' => false, 'response' => $errorsString];
        }

        $this->manager->persist($newCategory);
        $this->manager->flush();

        return ['status' => true, 'response' => ""];
    }
}
