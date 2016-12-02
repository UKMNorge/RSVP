<?php

namespace UKMNorge\RSVPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UKMNorge\UKMDipBundle\Entity\UserClass as BaseUser;

use stdClass;

/**
* @ORM\Entity
* @ORM\Table(name="dip_user")
*/
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(name="phone", type="integer", nullable=true, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="post_number", type="integer", nullable=true)
     */
    protected $postNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="post_place", type="string", length=255, nullable=true)
     */
    protected $postPlace;

    /**
     * @var integer
     *
     * @ORM\Column(name="birthdate", type="integer", nullable=true)
     */
    protected $birthdate = null;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    protected $facebook_id;
    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id_unencrypted", type="string", nullable=true)
     */
    protected $facebook_id_unencrypted;
    /** 
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=true)
     *
     */
    protected $gender;


    ### Returns an object with all variables we want to allow sending off-site.
    public function expose() {
        $u = new stdClass();
        $u->id = $this->getId();
        $u->delta_id = $this->getDeltaId();
        $u->first_name = $this->getFirstName();
        $u->last_name = $this->getLastName();
        $u->email = $this->getEmail();
        $u->post_number = $this->getPostNumber();
        $u->post_place = $this->getPostPlace();
        $u->phone = $this->getPhone();
        $u->birthdate = $this->getBirthdate();
        $u->facebook_id = $this->getFacebookId();
        $u->gender = $this->getGender();

        return $u;
    }

    public function setData($data) {
        $this->setPhone($data->phone);
        $this->setFacebookId($data->facebook_id);
    }

    /**
     * Set phone
     *
     * @param integer $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postNumber
     *
     * @param integer $postNumber
     * @return User
     */
    public function setPostNumber($postNumber)
    {
        $this->postNumber = $postNumber;

        return $this;
    }

    /**
     * Get postNumber
     *
     * @return integer 
     */
    public function getPostNumber()
    {
        return $this->postNumber;
    }

    /**
     * Set postPlace
     *
     * @param string $postPlace
     * @return User
     */
    public function setPostPlace($postPlace)
    {
        $this->postPlace = $postPlace;

        return $this;
    }

    /**
     * Get postPlace
     *
     * @return string 
     */
    public function getPostPlace()
    {
        return $this->postPlace;
    }

    /**
     * Set birthdate
     *
     * @param integer $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return integer 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }


    ### SECURITY-related methods!
    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getPassword() {
        // We don't use the password-functionality
        return hash('sha256', $this->password.$this->salt);
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->deltaId;
    }
    public function eraseCredentials() {
        // Not necessary to do anything.
    }


    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_id_unencrypted
     *
     * @param string $facebookIdUnencrypted
     * @return User
     */
    public function setFacebookIdUnencrypted($facebookIdUnencrypted)
    {
        $this->facebook_id_unencrypted = $facebookIdUnencrypted;

        return $this;
    }

    /**
     * Get facebook_id_unencrypted
     *
     * @return string 
     */
    public function getFacebookIdUnencrypted()
    {
        return $this->facebook_id_unencrypted;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    public function getThumbnail() {
	    return '//graph.facebook.com/'.$this->getFacebookId() .'/picture';
    }
    
    public function getImage() {
   	    return '//graph.facebook.com/'.$this->getFacebookId() .'/picture?type=large';
   	}
   	
   	public function getLink() {
	   	return '//facebook.com/profile.php?id='.$this->getFacebookId();
    }
}
