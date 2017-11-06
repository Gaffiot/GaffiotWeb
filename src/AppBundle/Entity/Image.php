<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="images")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many Subjects have One Section.
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="images")
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id")
     */
    private $word;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer",  nullable=false)
     */
    private $paragraph;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text",  nullable=false)
     */
    private $file;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Image
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     * @return Image
     */
    public function setWord($word)
    {
        $this->word = $word;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * @param mixed $paragraph
     * @return Image
     */
    public function setParagraph($paragraph)
    {
        $this->paragraph = $paragraph;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }


}