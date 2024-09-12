<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\UserInterface;
use App\Traits\HasTimestamps;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[Entity, Table('users')]
#[HasLifecycleCallbacks]
class User implements UserInterface
{
    use HasTimestamps;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $password;

    #[Column]
    private string $email;



    #[OneToMany(mappedBy:'user', targetEntity:Transaction::class)]
    private Collection $transactions;

    #[OneToMany(mappedBy:'user', targetEntity:Category::class)]
    private Collection $categories;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

  

    /**
     * Get the value of transactions
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Set the value of transactions
     *
     * @return  self
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions->add($transaction);

        return $this;
    }

    /**
     * Get the value of categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @return  self
     */
    public function addCategory(Category $category): User
    {
        $this->categories->add($category);

        return $this;
    }
}
