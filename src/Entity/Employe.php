<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEmbauche = null;

    #[ORM\ManyToOne(inversedBy: 'salaries')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function getDateNaissanceFr(): ?string
    {
        return $this->dateNaissance->format('d-m-y');
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTime
    {
        return $this->dateEmbauche;
    }

    public function getDateEmbaucheFr(): ?string
    {
        return $this->dateEmbauche->format('d-m-y');
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $id_entreprise): static
    {
        $this->entreprise = $id_entreprise;

        return $this;
    }

    public function __toString()
    {
        return $this->prenom." ".$this->nom;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAge(): ?string{
        $now = new \DateTime();
        $interval = $this->dateNaissance->diff($now);
        return $interval->format('%Y');
    }
}
