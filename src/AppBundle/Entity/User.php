<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Face", mappedBy="users")
     */
    private $proposals;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add proposal
     *
     * @param $proposal
     *
     * @return User
     */
    public function addProposal($proposal)
    {
        $this->proposals[] = $proposal;
        return $this;
    }

    /**
     * Remove proposal
     *
     * @param $proposal
     */
    public function removeProposal($proposal)
    {
        $this->proposals->removeElement($proposal);
    }

    /**
     * Get proposals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProposals()
    {
        return $this->proposals;
    }
}