<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="words")
 */
class Word
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
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $latin_raw;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $latin;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text",  nullable=false)
     */
    private $french;

    /**
     * One Section has Many Subjects.
     * @ORM\OneToMany(targetEntity="Image", mappedBy="word")
     * @ORM\OrderBy({"paragraph" = "ASC"})
     */
    private $images;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Word
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatinRaw()
    {
        return $this->latin_raw;
    }

    /**
     * @param mixed $latin_raw
     * @return Word
     */
    public function setLatinRaw($latin_raw)
    {
        $this->latin_raw = $latin_raw;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatin()
    {
        return $this->latin;
    }

    /**
     * @param mixed $latin
     * @return Word
     */
    public function setLatin($latin)
    {
        $this->latin = $latin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrench()
    {
        return $this->french;
    }

    /**
     * @param mixed $french
     * @return Word
     */
    public function setFrench($french)
    {
        $this->french = $french;
        return $this;
    }

}