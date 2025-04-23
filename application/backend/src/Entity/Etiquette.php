<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\EtiquetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EtiquetteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['etiquette:read']],
    denormalizationContext: ['groups' => ['etiquette:write']],
    order: ['libelle' => 'ASC'],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'libelle' => 'ipartial',
])]
class Etiquette
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    #[Groups(['etiquette:read', 'recette:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['etiquette:read', 'etiquette:write', 'recette:read'])]
    private string $libelle;

    #[ORM\Column(length: 7, nullable: true)]
    #[Groups(['etiquette:read', 'etiquette:write', 'recette:read'])]
    private ?string $color;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
