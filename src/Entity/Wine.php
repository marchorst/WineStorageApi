<?php

Namespace App\Entity;

use App\Repository\WineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;

#[ApiResource(
    //normalizationContext: ['groups' => ['wine:read']],
    //denormalizationContext: ['groups' => ['wine:write']],
)]
#[ORM\Entity(repositoryClass: WineRepository::class)]
class Wine
{
    #[Groups('wine:read', 'wine:write')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wineName = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(nullable: true)]
    private ?int $vintage = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $winery = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(nullable: true)]
    private ?int $alcoholByVolume = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column]
    private ?int $quantity = 0;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wineImage = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wineType = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[Groups('wine:read', 'wine:write')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $varietal = null;

    #[ORM\OneToMany(mappedBy: 'fieldcontext', targetEntity: CustomFieldValue::class, orphanRemoval: true)]
    private Collection $customFieldValues;

    public function __construct()
    {
        $this->customFieldValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWineName(): ?string
    {
        return $this->wineName;
    }

    public function setWineName(string $WineName): static
    {
        $this->wineName = $WineName;

        return $this;
    }

    public function getVintage(): ?int
    {
        return $this->vintage;
    }

    public function setVintage(?int $Vintage): static
    {
        $this->vintage = $Vintage;

        return $this;
    }

    public function getWinery(): ?string
    {
        return $this->winery;
    }

    public function setWinery(?string $winery): static
    {
        $this->winery = $winery;

        return $this;
    }

    public function getWineType(): ?string
    {
        return $this->wineType;
    }

    public function setWineType(?string $WineType): static
    {
        $this->wineType = $WineType;

        return $this;
    }

    public function getAlcoholByVolume(): ?int
    {
        return $this->alcoholByVolume;
    }

    public function setAlcoholByVolume(?int $AlcoholByVolume): static
    {
        $this->alcoholByVolume = $AlcoholByVolume;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->quantity = $Quantity;

        return $this;
    }

    public function getWineImage(): ?string
    {
        if($this->wineImage)    
            return $_ENV['wine_image_path']."/".$this->wineImage;
        else
            return "";
    }

    public function setWineImage(?string $WineImage): static
    {
        $this->wineImage = $WineImage;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $Country): static
    {
        $this->country = $Country;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $Price): static
    {
        $this->price = $Price;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $Region): static
    {
        $this->region = $Region;

        return $this;
    }

    public function getVarietal(): ?string
    {
        return $this->varietal;
    }

    public function setVarietal(?string $varietal): static
    {
        $this->varietal = $varietal;

        return $this;
    }

    /**
     * @return Collection<int, CustomFieldValue>
     */
    public function getCustomFieldValues(): Collection
    {
        return $this->customFieldValues;
    }

    public function addCustomFieldValue(CustomFieldValue $customFieldValue): static
    {
        if (!$this->customFieldValues->contains($customFieldValue)) {
            $this->customFieldValues->add($customFieldValue);
            $customFieldValue->setFieldcontext($this);
        }

        return $this;
    }

    public function removeCustomFieldValue(CustomFieldValue $customFieldValue): static
    {
        if ($this->customFieldValues->removeElement($customFieldValue)) {
            // set the owning side to null (unless already changed)
            if ($customFieldValue->getFieldcontext() === $this) {
                $customFieldValue->setFieldcontext(null);
            }
        }

        return $this;
    }
}
