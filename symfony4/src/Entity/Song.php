<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 * @ORM\Table(name="songs")
 * @ORM\HasLifecycleCallbacks
 */
class Song
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;
    const STATUS_SINGER_PASSIVE = 2;

    public static $statuses = [
        self::STATUS_ACTIVE         => 'Active',
        self::STATUS_PASSIVE        => 'Passive',
        self::STATUS_SINGER_PASSIVE => 'Singer Passive',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_SINGER = 10*60;
    const CD_ITEM = 60*60;
    const CD_RANDOM_LIST = 60*60;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $singer_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lyrics;

    /**
     * @ORM\Column(type="integer")
     */
    private $hit = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="Singer", inversedBy="songs", fetch="EAGER")
     */
    private $singer;

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = strtotime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSingerId(): ?int
    {
        return $this->singer_id;
    }

    public function setSingerId(int $singer_id): self
    {
        $this->singer_id = $singer_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getLyrics(): ?string
    {
        $lyrics = str_ireplace(["\n", "\r"], '', $this->lyrics);
        $lyrics = str_ireplace(['<br>', '<br/>', '<br >'], '<br />', $lyrics);
        $lyrics = str_ireplace('<br />', "\n", $lyrics);

        return $lyrics;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->lyrics = nl2br($lyrics);

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

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(int $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSinger(): ?Singer
    {
        return $this->singer;
    }

    public function setSinger(?Singer $singer): self
    {
        $this->singer = $singer;

        return $this;
    }
}
