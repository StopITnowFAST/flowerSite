<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\Table(name: '`cart`')]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:255)]
    private ?string $cart_id = null;

    #[ORM\Column]
    private ?int $flower_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCartId(): ?string
    {
        return $this->cart_id;
    }

    public function setCartId(string $cart_id): static
    {
        $this->cart_id = $cart_id;

        return $this;
    }

    public function getFlowerId(): ?int
    {
        return $this->flower_id;
    }

    public function setFlowerId(int $flower_id): static
    {
        $this->flower_id = $flower_id;

        return $this;
    }
}
