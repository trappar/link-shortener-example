<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(
 *     repositoryClass=LinkRepository::class,
 * )
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(columns={"code"}),
 *     }
 * )
 * @UniqueEntity("code")
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=30)
     * @Assert\Regex("~^[a-zA-Z0-9-]+$~")
     * @ORM\Column(type="string", length=30)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private string $secret;

    /**
     * @Assert\NotBlank
     * @Assert\Url
     * @ORM\Column(type="text")
     */
    private string $url;

    /**
     * @ORM\Column(type="integer")
     */
    private int $visits = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function addVisit(): self
    {
        $this->visits++;

        return $this;
    }
}
