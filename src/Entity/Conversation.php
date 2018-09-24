<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="conversation")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ConversationUsers", mappedBy="conversation")
     */
    private $conversationUsers;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->conversationUsers = new ArrayCollection();
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
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
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
            $conversationUser->addConversation($this);
        }

        return $this;
    }

    public function removeConversationUser(ConversationUsers $conversationUser): self
    {
        if ($this->conversationUsers->contains($conversationUser)) {
            $this->conversationUsers->removeElement($conversationUser);
            $conversationUser->removeConversation($this);
        }

        return $this;
    }
}
