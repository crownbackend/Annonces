<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $numberTelephone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="user", orphanRemoval=true)
     */
    private $advertisement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConversationUsers", mappedBy="user")
     */
    private $conversationUsers;

    public function __construct()
    {
        parent::__construct();
        $this->advertisement = new ArrayCollection();
        $this->conversationUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNumberTelephone(): ?string
    {
        return $this->numberTelephone;
    }

    public function setNumberTelephone(string $numberTelephone): self
    {
        $this->numberTelephone = $numberTelephone;

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
            $advertisement->setUser($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): self
    {
        if ($this->advertisement->contains($advertisement)) {
            $this->advertisement->removeElement($advertisement);
            // set the owning side to null (unless already changed)
            if ($advertisement->getUser() === $this) {
                $advertisement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConversationUsers[]
     */
    public function getConversationUsers(): Collection
    {
        return $this->conversationUsers;
    }

    public function addConversationUser(ConversationUsers $conversationUser): self
    {
        if (!$this->conversationUsers->contains($conversationUser)) {
            $this->conversationUsers[] = $conversationUser;
            $conversationUser->setUser($this);
        }

        return $this;
    }

    public function removeConversationUser(ConversationUsers $conversationUser): self
    {
        if ($this->conversationUsers->contains($conversationUser)) {
            $this->conversationUsers->removeElement($conversationUser);
            // set the owning side to null (unless already changed)
            if ($conversationUser->getUser() === $this) {
                $conversationUser->setUser(null);
            }
        }

        return $this;
    }

}
