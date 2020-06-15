<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 * @UniqueEntity("title")
 * @Vich\Uploadable
 *
 */
class Program
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Indiquer le nom de la série")
     * @Assert\Length(max="255", maxMessage="Le nom de série saisie {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères")
     * @Assert\Regex(pattern="/plus belle la vie/i", match=false, message="On parle de vraies séries ici")
     *
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Remplir le champ synopsis")
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $poster;

    /**
     * @Vich\UploadableField(mapping="poster_file", fileNameProperty="poster")
     * @var File
     */
    private $posterFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="programs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="program")
     */
    private $seasons;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Actor", mappedBy="programs")
     */
    private $actors;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->actors = new ArrayCollection();
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }
    public function setPosterFile(File $image = null)
    {
        $this->posterFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
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

    /**
     * @return Collection|Season[]
     */
    /**
     * @return Collection
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    /**
     * param Season $season
     * @return Program
     */
    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setProgram($this);
        }
        return $this;
    }

    /**
     * @param Season $season
     * @return Program
     */
    public  function removeSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
        }
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Actor[]
     */
    public function getActors(): \Doctrine\Common\Collections\Collection
    {
        return $this->actors;
    }


    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->contains($actor)) {
            $this->actors->removeElement($actor);
            $actor->removeProgram($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
