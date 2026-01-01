<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Filter\DateIntervalFilter;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['recette:read']],
    denormalizationContext: ['groups' => ['recette:write']],
    order: ['id' => 'DESC'],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'categorie' => 'exact',
    'ingredients' => 'exact',
    'etiquettes' => 'exact',
])]
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
    private string $libelle;

    #[ORM\OneToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups(['recette:read', 'recette:write'])]
    private ?Media $image = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Groups(['recette:read', 'recette:write'])]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?string $source = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $tempsDePreparation = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $tempsDeCuisson = null;

    #[ORM\Column(type: 'app_dateinterval', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?\DateInterval $pretDans = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    #[Groups(['recette:read', 'recette:write'])]
    private Collection $ingredients;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['recette:read', 'recette:write'])]
    private ?string $notes = null;

    /**
     * @var Collection<int, Etiquette>
     */
    #[ORM\ManyToMany(targetEntity: Etiquette::class)]
    #[Groups(['recette:read', 'recette:write'])]
    private Collection $etiquettes;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->etiquettes = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

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

    public function setPretDans(?\DateInterval $pretDans): self
    {
        $this->pretDans = $pretDans;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Etiquette>
     */
    public function getEtiquettes(): Collection
    {
        return $this->etiquettes;
    }

    public function addEtiquette(Etiquette $etiquette): static
    {
        if (!$this->etiquettes->contains($etiquette)) {
            $this->etiquettes->add($etiquette);
        }

        return $this;
    }

    public function removeEtiquette(Etiquette $etiquette): static
    {
        $this->etiquettes->removeElement($etiquette);

        return $this;
    }
}
