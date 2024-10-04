<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Filter\DateIntervalFilter;
use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['recette:read']],
    denormalizationContext: ['groups' => ['recette:write']],
    order: ['id' => 'DESC'],
)]
#[ApiFilter(DateIntervalFilter::class, properties: ['pretDans'])]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    #[Groups(['recette:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?string $libelle = null;

    #[ORM\OneToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups(['recette:read', 'recette:write'])]
    private ?Media $image = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Groups(['recette:read', 'recette:write'])]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $tempsDePreparation = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $tempsDeCuisson = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $pretDans = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getTempsDePreparation(): ?\DateInterval
    {
        return $this->tempsDePreparation;
    }

    public function setTempsDePreparation(?\DateInterval $tempsDePreparation): self
    {
        $this->tempsDePreparation = $tempsDePreparation;

        return $this;
    }

    public function getTempsDeCuisson(): ?\DateInterval
    {
        return $this->tempsDeCuisson;
    }

    public function setTempsDeCuisson(?\DateInterval $tempsDeCuisson): self
    {
        $this->tempsDeCuisson = $tempsDeCuisson;

        return $this;
    }

    public function getPretDans(): ?\DateInterval
    {
        return $this->pretDans;
    }

    public function setPretDans(\DateInterval $pretDans): self
    {
        $this->pretDans = $pretDans;

        return $this;
    }
}
