<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Entity;

class Attribute
{

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
     * @var string
     */
    protected $description;

    /**
     *
     * @var ArrayCollection
     */
    protected $groups;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name            
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *
     * @param string $label            
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     *
     * @return the string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getGroups()
    {
        if ($this->groups == null) {
            $this->groups = new ArrayCollection();
        }
        
        return $this->groups;
    }

    /**
     *
     * @param ArrayCollection $groups            
     */
    public function setGroups(ArrayCollection $groups)
    {
        $this->groups = $groups;
    }

    /**
     *
     * @param Group $group            
     */
    public function addGroup(Group $group)
    {
        $this->getGroups()->add($group);
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