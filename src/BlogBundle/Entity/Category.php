<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

	/**
	 *@ORM\OneToMany(targetEntity="Post", mappedBy="category", cascade={"remove"})
	 */
	private $posts;

	/**
	 *@ORM\OneToMany(targetEntity="Comment", mappedBy="category",  cascade={"remove"})
	 */
	private $comments;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
	}

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set description
     *
     * @param string $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
	 * Add comments
	 *
	 * @param Comment $comments
	 * @return Category
	 */
	public function addComment(Comment $comment)
	{
		$this->comments[] = $comment;
		$comment->setCategory($this);
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * @return mixed
	 */
	public function getPosts()
	{
		return $this->posts;
	}

}

