<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConversationUsersRepository")
 */
class ConversationUsers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="conversationUsers")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Conversation", inversedBy="conversationUsers")
     */
    private $conversation;

    public function __construct()
    {
        $this->conversation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Conversation[]
     */
    public function getConversation(): Collection
    {
        return $this->conversation;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversation->contains($conversation)) {
            $this->conversation[] = $conversation;
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversation->contains($conversation)) {
            $this->conversation->removeElement($conversation);
        }

        return $this;
    }
}
