<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CustomFieldValueRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CustomFieldValueRepository::class)]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['fieldcontext' => 'exact'])]
class CustomFieldValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldname = null;

    #[ORM\Column(length: 9999, nullable: true)]
    private ?string $fieldvalue = null;

    #[ORM\ManyToOne(inversedBy: 'customFieldValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wine $fieldcontext = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldname(): ?string
    {
        return $this->fieldname;
    }

    public function setFieldname(string $fieldname): static
    {
        $this->fieldname = $fieldname;

        return $this;
    }

    public function getFieldvalue(): ?string
    {
        return $this->fieldvalue;
    }

    public function setFieldvalue(?string $fieldvalue): static
    {
        $this->fieldvalue = $fieldvalue;

        return $this;
    }

    public function getFieldcontext(): ?Wine
    {
        return $this->fieldcontext;
    }

    public function setFieldcontext(?Wine $fieldcontext): static
    {
        $this->fieldcontext = $fieldcontext;

        return $this;
    }

}
