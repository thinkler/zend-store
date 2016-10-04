<?php

namespace Order\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="orders")
 * @property integer $quantity
 * @property float $totalPrice
 * @property string $itemsIds
 * @property integer $userId
 * @property integer $id
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="float")
     */
    protected $totalPrice;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @ORM\Column(type="string")
     */
    protected $itemsIds;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userId;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __isset($property)
    {
        if ($this->property = $property)
        {
            return true;
        }
        return false;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array())
    {
        $this->quantity = $data['quantity'];
        $this->totalPrice = $data['totalPrice'];
        $this->itemsIds = $data['itemsIds'];
    }

}
