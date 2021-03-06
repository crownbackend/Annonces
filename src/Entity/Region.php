<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=255, type="string")
     */
    private $regionSlug;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $class;

    /**
     * @ORM\Column(type="text")
     */
    private $d;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="region", orphanRemoval=true)
     */
    private $advertisement;
    public function __construct()
    {
        $this->advertisement = new ArrayCollection();
    }

    public function __toString()
    {
        if(is_null($this->name)){
            return 'NULL';
        }
       return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRegionSlug()
    {
        return $this->regionSlug;
    }

    /**
     * @return Collection|Advertisement[]
     */
    public function getAdvertisement(): Collection
    {
        return $this->advertisement;
    }

    public function addAdvertisement(Advertisement $advertisement): self
    {
        if (!$this->advertisement->contains($advertisement)) {
            $this->advertisement[] = $advertisement;
            $advertisement->setRegion($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): self
    {
        if ($this->advertisement->contains($advertisement)) {
            $this->advertisement->removeElement($advertisement);
            // set the owning side to null (unless already changed)
            if ($advertisement->getRegion() === $this) {
                $advertisement->setRegion(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getD(): ?string
    {
        return $this->d;
    }

    public function setD(string $d): self
    {
        $this->d = $d;

        return $this;
    }

}
