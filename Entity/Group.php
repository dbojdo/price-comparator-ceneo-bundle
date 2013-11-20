<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Entity;

use Webit\PriceComparator\Ceneo\Model\Group as OfferGroup;
use Doctrine\Common\Collections\ArrayCollection;

class Group {
    
    /**
     * 
     * @var int
     */
    protected $id;
    
    /**
     * 
     * @var string
     */
    protected $name;
    
    /**
     * 
     * @var string
     */
    protected $label;

    /**
     *
     * @var \DateTime
     */
    protected $createdAt;
    
    /**
     *
     * @var \DateTime
     */
    protected $updatedAt;
    
    /**
     * 
     * @var ArrayCollection
     */
    protected $attributes;
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * 
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * 
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }
    
    /**
     * 
     * @param string $label
     */
    public function setLabel($label) {
        $this->label = $label;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getAttributes() {
        if($this->attributes == null) {
            $this->attributes = new ArrayCollection();
        }
        
        return $this->attributes;
    }
    
    /**
     * 
     * @param ArrayCollection $attributes
     */
    public function setAttributes(ArrayCollection $attributes) {
        $this->attributes = $attributes;
    }
    
    /**
     * @param Attribute $attribute
     */
    public function addAttribute(Attribute $attribute) {
        $this->getAttributes()->add($attribute);
    }

    /**
     * 
     * @return \Webit\PriceComparator\Ceneo\Model\Group
     */
    public function createOfferGroup() {
        $group = new OfferGroup();
        $group->setName($this->getName());
        
        return $group;
    }
    
    /**
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}