<?php
/**
 * Movie entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Movie.
 *
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movies")
 *
 * @UniqueEntity(fields={"title"})
 */
class Movie
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Created at.
     *
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $title;

    /**
     * Category.
     *
     * @var \App\Entity\Category Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Category",
     *     inversedBy="movies",
     *     fetch="EXTRA_LAZY",
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="movies",
     *     orphanRemoval=true,
     *     fetch="EXTRA_LAZY",
     * )
     * @ORM\JoinTable(name="movies_tags")
     */
    private $tags;

    /**
     * Comment.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",
     *      mappedBy="movie",
     *      orphanRemoval=true)
     */
    private $comment;


    /**
     * Movie constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comment = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Created At.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param \DateTimeInterface $createdAt Created at
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Director.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     */
    private $director;

    /**
     * Getter for Director.
     *
     * @return string|null Director
     */
    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * Setter for Director.
     *
     * @param string $director Director
     */
    public function setDirector(string $director): void
    {
        $this->director = $director;
    }


    /**
     * Description.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255
     * )
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $description;

    /**
     * Getter for Description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @param string $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }



    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    /**
     * Getter for Updated at.
     *
     * @return \DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated at.
     *
     * @param \DateTimeInterface $updatedAt Updated at
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for category.
     *
     * @return \App\Entity\Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param \App\Entity\Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for tags.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Tag[] Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag from collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

//
//    /**
//     * Setter for comment.
//     *
//     * @param \App\Entity\Comment|null $comment Comment
//     */
//    public function setComment(?Comment $comment): void
//    {
//        $this->comment = $comment;
//    }

    /**
     * Getter for Comment.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Comment[] Comment collection
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    /**
     * Add comment to collection.
     *
     * @param Comment $comment Comment
     */
    public function addComment(Comment $comment): void
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setMovie($this);
        }
    }

//    /**
//     * Remove comment from collection.
//     *
//     * @param \App\Entity\Comment $comment Comment
//     */
//    public function removeComment(Comment $comment): void
//    {
//        if ($this->comment->contains($comment)) {
//            $this->comment->removeElement($comment);
//            // set the owning side to null (unless already changed)
//            if ($comment->getMovie() === $this) {
//                $comment->setMovie(null);
//            }
//        }
//    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }



}

