<?php

namespace App\Models;

class Order
{
    private int $id;
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
