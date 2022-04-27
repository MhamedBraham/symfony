<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="categorie")
     */
    private $Products;

    public function __construct()
    {
        $this->Products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getProducts(): Collection
    {
        return $this->Products;
    }

    public function addProduct(product $Product): self
    {
        if (!$this->Products->contains($Product)) {
            $this->Products[] = $Product;
            $Product->setCategorie($this);
        }

        return $this;
    }

    public function removeProduct(product $Product): self
    {
        if ($this->Products->removeElement($Product)) {
            // set the owning side to null (unless already changed)
            if ($Product->getCategorie() === $this) {
                $Product->setCategorie(null);
            }
        }

        return $this;
    }
}
