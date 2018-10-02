<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdvertisementRepository")
 * @ORM\Table(name="advertisement")
 * @Vich\Uploadable
 */
class Advertisement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "Il faut au minimum {{ limit }} charactère",
     *      maxMessage = "Il faut au maximum {{ limit }} charactère"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min = 50,
     *     max = 3500,
     *     minMessage = "Il faut au minimum {{ limit }} charactère",
     *     maxMessage = "Il faut au maximum {{ limit }} charactère"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     * @Assert\Regex(
     *     pattern="/^[0-9]+(\.[0-9]{2,10})?$/",
     *     message="Le prix n'est pas conforme !"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 7,
     *     max = 70,
     *     minMessage = "Votre addresse n'est pas valide",
     *     maxMessage = "Votre addresse est trop longue",
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    /**
     * @Gedmo\Slug(fields={"title"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=255, type="string")
     */
    private $advertisementSlug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="advertisement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="advertisement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="advertisement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->isValid = 0;
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getAdvertisementSlug()
    {
        return $this->advertisementSlug;
    }

    // The file images of annoncement
    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Vous pouvez ajouter les extension suivante : JPEG, PNG"
     * )
     * @Vich\UploadableField(mapping="advertisement", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @throws \Exception
     */
    public function setImageFile(?File $image = null): void
    {
        $this->imageFile = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    // second image
    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Vous pouvez ajouter les extension suivante : JPEG, PNG"
     * )
     * @Vich\UploadableField(mapping="advertisement2", fileNameProperty="imageName2", size="imageSize2")
     *
     * @var File
     */
    private $imageFile2;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName2;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize2;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt2;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image2
     * @throws \Exception
     */
    public function setImageFile2(?File $image2 = null): void
    {
        $this->imageFile2 = $image2;

        if (null !== $image2) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt2 = new \DateTimeImmutable();
        }
    }

    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    public function setImageName2(?string $imageName2): void
    {
        $this->imageName2 = $imageName2;
    }

    public function getImageName2(): ?string
    {
        return $this->imageName2;
    }

    public function setImageSize2(?int $imageSize2): void
    {
        $this->imageSize2 = $imageSize2;
    }

    public function getImageSize2(): ?int
    {
        return $this->imageSize2;
    }

    // 3rd

    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Vous pouvez ajouter les extension suivante : JPEG, PNG"
     * )
     * @Vich\UploadableField(mapping="advertisement3", fileNameProperty="imageName3", size="imageSize3")
     *
     * @var File
     */
    private $imageFile3;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName3;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize3;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt3;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image3
     * @throws \Exception
     */
    public function setImageFile3(?File $image3 = null): void
    {
        $this->imageFile3 = $image3;

        if (null !== $image3) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt3 = new \DateTimeImmutable();
        }
    }

    public function getImageFile3(): ?File
    {
        return $this->imageFile3;
    }

    public function setImageName3(?string $imageName3): void
    {
        $this->imageName3 = $imageName3;
    }

    public function getImageName3(): ?string
    {
        return $this->imageName3;
    }

    public function setImageSize3(?int $imageSize3): void
    {
        $this->imageSize3 = $imageSize3;
    }

    public function getImageSize3(): ?int
    {
        return $this->imageSize3;
    }

    // 4
    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Vous pouvez ajouter les extension suivante : JPEG, PNG"
     * )
     * @Vich\UploadableField(mapping="advertisement4", fileNameProperty="imageName4", size="imageSize4")
     *
     * @var File
     */
    private $imageFile4;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName4;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize4;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt4;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="advertisement", orphanRemoval=true)
     */
    private $messages;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image4
     * @throws \Exception
     */
    public function setImageFile4(?File $image4 = null): void
    {
        $this->imageFile4 = $image4;

        if (null !== $image4) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt4 = new \DateTimeImmutable();
        }
    }

    public function getImageFile4(): ?File
    {
        return $this->imageFile4;
    }

    public function setImageName4(?string $imageName4): void
    {
        $this->imageName4 = $imageName4;
    }

    public function getImageName4(): ?string
    {
        return $this->imageName4;
    }

    public function setImageSize4(?int $imageSize4): void
    {
        $this->imageSize4 = $imageSize4;
    }

    public function getImageSize4(): ?int
    {
        return $this->imageSize4;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAdvertisement($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAdvertisement() === $this) {
                $message->setAdvertisement(null);
            }
        }

        return $this;
    }

}
