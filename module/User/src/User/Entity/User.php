<?php

namespace User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Hydrator;
use Zend\Math\Rand;
use Zend\Crypt\Key\Derivation\Pbkdf2;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="User\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", columnDefinition="TINYINT(1) DEFAULT 0 NOT NULL", nullable=false, options={"comment" = "1-Available/2-Away/3-Busy"})
     */
    private $status = 1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_access", type="datetime", nullable=false)
     */
    private $lastAccess;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\User", mappedBy="friends")
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\User", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="user_friend",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $friends;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="User\Entity\News", mappedBy="user", cascade={"persist"})
     */
    private $news;

    /**
     * Constructor
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->salt = base64_encode(Rand::getBytes(8, true));

        $hydrator = new Hydrator\ClassMethods();
        $hydrator->hydrate($options, $this);

        $this->created    = new \DateTime("now");
        $this->lastAccess = new \DateTime("now");

        $this->news    = new ArrayCollection();
        $this->added   = new ArrayCollection();
        $this->addedMe = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return this
     */
    public function setPassword($password)
    {
        $this->password = $this->encryptPassword($password);

        return $this;
    }

    public function encryptPassword($password)
    {
        return base64_encode(Pbkdf2::calc('sha256', $password, $this->salt, 10000, strlen($password*2)));
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     * @return this
     * @internal param \DateTime $created
     */
    public function setCreated()
    {
        $this->created = new \DateTime("now");

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastAccess
     *
     * @ORM\PrePersist
     * @return this
     * @internal param \DateTime $lastAccess
     */
    public function setLastAccess()
    {
        $this->lastAccess = new \DateTime("now");

        return $this;
    }

    /**
     * Get lastAccess
     *
     * @return \DateTime
     */
    public function getAccess()
    {
        return $this->lastAccess;
    }

    /**
     * Add a relationship User-Friend
     *
     * @param \User\Entity\User $friends
     * @return this
     */
    public function addFriend(\User\Entity\User $friend)
    {
        $this->friends[] = $friend;
        $friend->addUser($this);

        return $this;
    }

    /**
     * Add a relationship User-Friend
     *
     * @param \User\Entity\User $friends
     * @return this
     */
    public function addUser(\User\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove a relationship User-Friend
     *
     * @param \User\Entity\User $friend
     * @return this
     */
    public function removeFriend(\User\Entity\User $friend)
    {
        if (!$this->friends->contains($friend))
            return $this;

        $this->friends->removeElement($friend);

        return $this;
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $hydrator = new Hydrator\ClassMethods();
        $array    = $hydrator->extract($this);
        unset($array['password'], $array['salt'], $array['users'], $array['friends']);

        return $array;
    }
}
