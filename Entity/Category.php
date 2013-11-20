<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Entity;

use Webit\PriceComparator\Ceneo\Model\Category as BaseCategory;
use Webit\PriceComparator\Ceneo\Model\CategoryPath;

class Category extends BaseCategory
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
    protected $path;

    /**
     * 
     * @var bool
     */
    protected $enabled = true;
    
    /**
     * 
     * @var int
     */
    protected $treeRoot;
    
    /**
     *
     * @var int
     */
    protected $treeLeft;

    /**
     *
     * @var int
     */
    protected $treeRight;

    /**
     *
     * @var int
     */
    protected $treeLevel;

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

    public function getId()
    {
        return $this->id;
    }

    public function getPath() {
        if($this->path == null) {
            $this->updatePath();
        }
        
        return $this->path;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }
    
    /**
     * 
     * @param bool $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }
    
    private function updatePath() {
        $this->path = parent::getPath();
    }
    
    /**
     * 
     * @return int
     */
    public function getTreeRoot() {
        return $this->treeRoot;
    }
    
    /**
     * 
     * @param int $treeRoot
     */
    public function setTreeRoot($treeRoot) {
        $this->treeRoot = $treeRoot;    
    }
    
    /**
     *
     * @return int
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     *
     * @param int $treeLeft            
     */
    public function setTreeLeft($treeLeft)
    {
        $this->treeLeft = $treeLeft;
    }

    /**
     *
     * @return int
     */
    public function getTreeRight()
    {
        return $this->treeRight;
    }

    /**
     *
     * @param int $treeRight            
     */
    public function setTreeRight($treeRight)
    {
        $this->treeRight = $treeRight;
    }

    /**
     *
     * @return int
     */
    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    /**
     *
     * @param int $treeLevel            
     */
    public function setTreeLevel($treeLevel)
    {
        $this->treeLevel = $treeLevel;
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
    
    /**
     * 
     * @return CategoryPath
     */
    public function createCategoryPath() {
        return new CategoryPath($this->getPath());
    }
    
    /**
     * PrePersist and PreUpdate LifeCycleCallback
     */
    public function preSave() {
        $this->updatePath();
    }
}