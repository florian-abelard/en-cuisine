<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RealisationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['realisation:read']],
    denormalizationContext: ['groups' => ['realisation:write']],
    order: ['date' => 'DESC'],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'recette' => 'exact',
])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
class Realisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    #[Groups(['realisation:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Recette::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['realisation:read', 'realisation:write'])]
    private Recette $recette;

    #[ORM\Column(type: 'date')]
    #[Groups(['realisation:read', 'realisation:write'])]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['realisation:read', 'realisation:write'])]
    private ?string $notes = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\ManyToMany(targetEntity: Media::class)]
    #[Groups(['realisation:read', 'realisation:write'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecette(): Recette
    {
        return $this->recette;
    }

    public function setRecette(Recette $recette): self
    {
        $this->recette = $recette;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
     * @return Collection<int, Media>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Media $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(Media $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }
}
