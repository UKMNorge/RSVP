<?php

namespace UKMNorge\RSVPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constrains as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Response
 *
 * @ORM\Table(name="response")
 * @ORM\Entity(repositoryClass="UKMNorge\RSVPBundle\Repository\ResponseRepository")
 * @UniqueEntity(fields={"event", "user"})
 */
class Response
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @var int
     *
     * @ORM\Column(name="User", type="integer")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="Status", type="string", length=5)
     */
    private $status;


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
     * Set event
     *
     * @param integer $event
     *
     * @return Response
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return int
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Response
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Response
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

