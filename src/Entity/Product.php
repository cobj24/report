<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entitet som representerar en produkt.
 *
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /**
     * Produktens ID (primärnyckel).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Produktens namn.
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Produktens värde/pris.
     */
    #[ORM\Column(type: 'integer')]
    private ?int $value = null;

    /**
     * Hämtar produktens ID.
     *
     * @return int|null produkt-ID eller null om ej satt
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Hämtar produktens namn.
     *
     * @return string|null produktnamn
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sätter produktens namn.
     *
     * @param string $name produktnamn
     *
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Hämtar produktens värde/pris.
     *
     * @return int|null produktvärde
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Sätter produktens värde/pris.
     *
     * @param int $value produktvärde
     *
     * @return $this
     */
    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
