<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entitet som representerar en bok.
 *
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    /**
     * Bok-ID (primärnyckel).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Bokens titel.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Bokens ISBN-nummer.
     */
    #[ORM\Column(length: 13)]
    private ?string $isbn = null;

    /**
     * Bokens författare.
     */
    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * URL eller sökväg till bokomslagsbilden.
     */
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * Hämtar bok-ID.
     *
     * @return int|null bok-ID eller null om ej satt
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Hämtar boktitel.
     *
     * @return string|null boktitel
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sätter boktitel.
     *
     * @param string $title ny titel
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Hämtar ISBN.
     *
     * @return string|null ISBN-nummer
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Sätter ISBN.
     *
     * @param string $isbn nytt ISBN-nummer
     *
     * @return $this
     */
    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Hämtar författarens namn.
     *
     * @return string|null författare
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Sätter författarens namn.
     *
     * @param string $author ny författare
     *
     * @return $this
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Hämtar bildens URL eller sökväg.
     *
     * @return string|null bild
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sätter bildens URL eller sökväg.
     *
     * @param string $image bildens URL eller sökväg
     *
     * @return $this
     */
    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
