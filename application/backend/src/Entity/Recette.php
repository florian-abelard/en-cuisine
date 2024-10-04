<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['recette:read']],
    denormalizationContext: ['groups' => ['recette:write']],
    order: ['id' => 'DESC'],
)]
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

    #[ORM\Column(type: 'dateinterval', nullable: true)]
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

    public function getPretDans(): ?\DateInterval
    {
        return $this->pretDans;
    }

    public function setPretDans(\DateInterval $pretDans): self
    {
        $this->pretDans = $pretDans;

        return $this;
    }

    #[Groups(['recette:read'])]
    #[SerializedName('pretDans')]
    public function getPretDansFormatted(): ?string
    {
        if ($this->pretDans === null) {
            return null;
        }

        if ($this->pretDans->format('%a') > '0') {
            return $this->pretDans->format('%a jours');
        }

        if ($this->pretDans->format('%h') > '0') {
            return $this->pretDans->format('%h heures');
        }

        return $this->pretDans->format('%i minutes');
    }

    #[Groups(['recette:write'])]
    #[SerializedName('pretDans')]
    public function setPretDansFormatted(string $pretDansFormatted): self
    {
        $pretDansFormatted = strtolower($pretDansFormatted);
        $pretDansFormatted = preg_replace('/(\d+)([a-z])/', '$1 $2', $pretDansFormatted);
        $pretDansFormatted = preg_replace(['/jours$/', '/jour$/', '/j$/'], 'days', $pretDansFormatted);
        $pretDansFormatted = preg_replace(['/heures$/', '/heure$/', '/h$/'], 'hours', $pretDansFormatted);
        $pretDansFormatted = preg_replace(['/min$/', '/m$/'], 'minutes', $pretDansFormatted);

        $this->pretDans = \DateInterval::createFromDateString($pretDansFormatted) ?? null;

        return $this;
    }
}
