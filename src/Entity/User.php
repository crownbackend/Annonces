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
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="user", orphanRemoval=true)
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Conversations", inversedBy="users")
     */
    private $conversation;

    public function __construct()
    {
        parent::__construct();
        $this->advertisement = new ArrayCollection();
        $this->message = new ArrayCollection();
        $this->conversation = new ArrayCollection();
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
     * @return Collection|Messages[]
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->message->contains($message)) {
            $this->message[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->message->contains($message)) {
            $this->message->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversations[]
     */
    public function getConversation(): Collection
    {
        return $this->conversation;
    }

    public function addConversation(Conversations $conversation): self
    {
        if (!$this->conversation->contains($conversation)) {
            $this->conversation[] = $conversation;
        }

        return $this;
    }

    public function removeConversation(Conversations $conversation): self
    {
        if ($this->conversation->contains($conversation)) {
            $this->conversation->removeElement($conversation);
        }

        return $this;
    }

}
