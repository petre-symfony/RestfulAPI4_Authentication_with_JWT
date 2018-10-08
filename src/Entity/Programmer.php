<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use App\Annotation\Link;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgrammerRepository")
 * @Serializer\ExclusionPolicy("all")
 * @Link(
 *   "self",
 *   route = "api_programmers_show",
 *   params = { "nickname": "object.getNickname()"}
 * )
 */
class Programmer {
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank(message="Please enter a clever nickname!")
	 * @Serializer\Expose()
   */
  private $nickname;

  /**
   * @ORM\Column(type="integer")
	 * @Serializer\Expose()
   */
  private $avatarNumber;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Expose()
   */
  private $tagLine;

  /**
   * @ORM\Column(type="integer")
	 * @Serializer\Expose()
   */
  private $powerLevel = 0;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Expose()
	 * @Serializer\Groups("deep")
   */
  private $user;

	public function __construct($nickname = null, $avatarNumber = null){
		$this->nickname = $nickname;
		$this->avatarNumber = $avatarNumber;
	}

  public function getId(): ?int {
    return $this->id;
  }

  public function getNickname(): ?string {
    return $this->nickname;
  }

  public function setNickname(string $nickname): self {
    $this->nickname = $nickname;

    return $this;
  }

  public function getAvatarNumber(): ?int {
    return $this->avatarNumber;
  }

  public function setAvatarNumber(int $avatarNumber): self {
    $this->avatarNumber = $avatarNumber;

    return $this;
  }

  public function getTagLine(): ?string {
    return $this->tagLine;
  }

  public function setTagLine(?string $tagLine): self {
    $this->tagLine = $tagLine;

    return $this;
  }

  public function getPowerLevel(): ?int {
    return $this->powerLevel;
  }

  public function setPowerLevel(int $powerLevel): self{
    $this->powerLevel = $powerLevel;

    return $this;
  }

  public function getUser(): ?User {
    return $this->user;
  }

  public function setUser(?User $user): self {
    $this->user = $user;

    return $this;
  }
}
