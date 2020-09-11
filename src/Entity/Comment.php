<?php
/**
 * Comment entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment
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
     * Created At.
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
     * Updated At.
     *
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $content;

    /**
     * User.
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="comment"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Movie.
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Movie",
     *     inversedBy="comment"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;


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
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created At.
     *
     * @param \DateTimeInterface $createdAt Created at
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Updated At.
     *
     * @return \DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated At.
     *
     * @param \DateTimeInterface $updatedAt Updated at
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string $content Content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * Getter for User.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for User.
     *
     * @param User|null $user User
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for Movie.
     *
     * @return Movie|null Movie
     */
    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    /**
     * Setter for Movie.
     *
     * @param Movie|null $movie Movie
     */
    public function setMovie(?Movie $movie): void
    {
        $this->movie = $movie;
    }

}
