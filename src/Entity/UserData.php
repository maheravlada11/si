<?php
/**
 * UserData entity.
 */
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserData
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserDataRepository")
 * @ORM\Table(name="user_data")
 *
 * @UniqueEntity(fields={"nickname"})
 */
class UserData
{
    /**
     * Primary key.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=30,
     *     nullable=true
     * )
     *
     * @Assert\Type("string")
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $name;

    /**
     * Surname.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45,
     *     nullable=true
     * )
     *
     * @Assert\Type("string")
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $surname;

    /**
     * Nickname.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=50
     * )
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     */
    private $nickname;

    /**
     * User.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="userData", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for Surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string|null $surname Surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Getter for Nickname.
     *
     * @return string|null Nickname
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Setter for Nickname.
     *
     * @param string $nickname Nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        //if ($user->getUserData() !== $this) {
        //    $user->setUserData($this);
        // }
    }
}
