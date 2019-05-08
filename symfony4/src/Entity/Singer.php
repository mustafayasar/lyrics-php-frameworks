<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SingerRepository")
 * @ORM\Table(name="singers")
 */
class Singer
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;

    public static $statuses = [
        self::STATUS_PASSIVE    => 'Passive',
        self::STATUS_ACTIVE     => 'Active',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_ITEM = 60*60;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $hit = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getHit(): ?int
    {
        return $this->hit;
    }

    public function setHit(int $hit): self
    {
        $this->hit = $hit;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
