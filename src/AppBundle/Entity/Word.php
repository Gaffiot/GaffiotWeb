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
     * One Word has Many Images.
     * @ORM\OneToMany(targetEntity="Image", mappedBy="word")
     * @ORM\OrderBy({"paragraph" = "ASC"})
     */
    private $images;

    /**
     * Many Words have Many Pages.
     * @ORM\ManyToMany(targetEntity="Page", inversedBy="words")
     * @ORM\JoinTable(name="words_pages")
     */
    private $pages;

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

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     * @return Word
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @param mixed $pages
     * @return Word
     */
    public function updatePages($pages)
    {
        if (!empty($this->getPages())) {
            $new = array_merge($pages, $this->getPages()->getValues());
        } else {
            $new = $pages;
        }
        $this->pages = $new;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     * @return Word
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
        return $this;
    }
}