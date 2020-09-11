<?php
/**
 * User entity.
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 *
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * Role user.
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin.
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     */
    private $id;

    /**
     * E-mail.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=180,
     *     unique=true,
     * )
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="5",
     *     max="180",
     * )
     */
    private $email;

    /**
     * Roles.
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * The hashed password.
     *
     * @var string
     *
     * @ORM\Column(type="string")

     * @Assert\NotBlank
     *
     * @SecurityAssert\UserPassword(groups={"password"})
     *
     * @Assert\Length(
     *     min="6",
     *     max="255",
     * )
     */
    private $password;

    /**
     * Comment.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comment;

    /**
     * UserData.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\UserData", mappedBy="user", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $userData;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    /**
     * Getter for the Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for the E-mail.
     *
     * @return string|null E-mail
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for the E-mail.
     *
     * @param string $email E-mail
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string User name
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for the Roles.
     *
     * @see UserInterface
     *
     * @return array Roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * Setter for the Roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for the Password.
     *
     * @see UserInterface
     *
     * @return string|null Password
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Setter for the Password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter for comment.
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
     * @param \App\Entity\Comment $comment Comment entity
     */
    public function addComment(Comment $comment): void
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setUser($this);
        }
    }

    /**
     * Remove comment from collection.
     *
     * @param \App\Entity\Comment $comment Comment entity
     */
    public function removeComment(Comment $comment): void
    {
        if ($this->comment->contains($comment)) {
            $this->comment->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }
    }


    /**
     * Getter for User Data.
     *
     * @return \App\Entity\UserData|null User data entity
     */
    public function getUserData(): ?UserData
    {
        return $this->userData;
    }

    /**
     * Setter for User Data.
     *
     * @param \App\Entity\UserData $userData User data entity
     */
    public function setUserData(UserData $userData): void
    {
        $this->userData = $userData;

        if ($this !== $userData->getUser()) {
            $userData->setUser($this);
        }

    }

}