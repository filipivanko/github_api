<?php

namespace App\Entity;

use App\Repository\ResultsRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultsRecordRepository::class)]
class ResultsRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $search_term = null;

    #[ORM\Column]
    private ?float $score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSearchTerm(): ?string
    {
        return $this->search_term;
    }

    public function setSearchTerm(string $search_term): self
    {
        $this->search_term = $search_term;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }
}
