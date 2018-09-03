<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="region")
     */
    private $advertisement;

    public function __construct()
    {
        $this->advertisement = new ArrayCollection();
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
}
