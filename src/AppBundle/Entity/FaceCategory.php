<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaceCategory
 *
 * @ORM\Table(name="face_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FaceCategoryRepository")
 */
class FaceCategory
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean", nullable=true)
     */
    private $enable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Face", mappedBy="category")
     */
    private $faces;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->faces = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return FaceCategory
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    public function __toString()
    {
        return $this->titre.'';
    }
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return FaceCategory
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return FaceCategory
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return FaceCategory
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add face
     *
     * @param \AppBundle\Entity\Face $face
     *
     * @return FaceCategory
     */
    public function addFace(Face $face)
    {
        $this->faces[] = $face;

        return $this;
    }

    /**
     * Remove face
     *
     * @param \AppBundle\Entity\Face $face
     */
    public function removeFace(Face $face)
    {
        $this->faces->removeElement($face);
    }

    /**
     * Get faces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaces()
    {
        return $this->faces;
    }
}
