<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Synchronizer;

use Symfony\Component\Yaml\Yaml;
use Webit\Bundle\PriceComparatorCeneoBundle\Entity\Attribute;
use Webit\Bundle\PriceComparatorCeneoBundle\Entity\Group;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\OutputInterface;

class GroupAttributeSynchronizer implements GroupAttributeSynchronizerInterface {
    /**
     * 
     * @var EntityManager
     */
    private $em;
    
    /**
     *
     * @var Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function setOutput(OutputInterface $output) {
        $this->output = $output;
    }
    
    public function synchronizeGroupsAndAttributes() {
        $attributes = $this->synchronizeAttributes();
        $this->synchronizeGroups($attributes);
    }
    
    private function synchronizeAttributes() {
        $attributes = $this->getAttributes();
        
        $inputFile = __DIR__.'/../Resources/static/attributes.yml';
        $arAttributes = Yaml::parse($inputFile);
        
        $arCurrent = array();
        foreach($arAttributes as $arAttr) {
            $this->showMsg('Synchronizing attribute: '.$arAttr['name']);
            $attr = isset($attributes[$arAttr['name']]) ? $attributes[$arAttr['name']] : new Attribute();
            $attr->setName($arAttr['name']);
            $attr->setLabel($arAttr['label']);
            $attr->setDescription($arAttr['description']);
            
            $this->em->persist($attr);
            $arCurrent[$attr->getName()] = $attr;;
        }
        $toRemove = array_diff(array_keys($attributes), array_keys($arCurrent));
        foreach ($toRemove as $k) {
            $this->em->remove($attributes[$k]);
        }
        $this->em->flush();
        
        return $arCurrent;
    }
    
    private function getAttributes() {
        $arAttributes = $this->em->getRepository('Webit\Bundle\PriceComparatorCeneoBundle\Entity\Attribute')->findAll();
        $attrByName = array();
        foreach($arAttributes as $attr) {
            $attrByName[$attr->getName()] = $attr;
        }
        
        return $attrByName;
    }
    
    private function synchronizeGroups(array $attributes) {
        $groups = $this->getGroups();
        $inputFile = __DIR__.'/../Resources/static/groups.yml';
        $arGroups = Yaml::parse($inputFile);
        
        $arCurrent = array();
        foreach($arGroups as $arGroup) {
            $this->showMsg('Synchronizing group: '.$arGroup['name']);
            $group = isset($groups[$arGroup['name']]) ? $groups[$arGroup['name']] : new Group();
            $group->setName($arGroup['name']);
            $group->setLabel($arGroup['label']);
            $group->getAttributes()->clear();
            foreach($arGroup['attributes_codes'] as $attrName) {
                $this->showMsg(sprintf('Binds attribute "%s" to group "%s"',$attrName, $arGroup['name']));
                $attr = $attributes[$attrName];
                $group->getAttributes()->add($attr);
            }
            $arCurrent[$group->getName()] = $group;
            
            $this->em->persist($group);
        }

        $toRemove = array_diff(array_keys($groups), array_keys($arCurrent));
        foreach ($toRemove as $k) {
            $this->em->remove($groups[$k]);
        }
        
        $this->em->flush();
        
        return $arCurrent;
    }
    
    private function getGroups() {
        $arGroups = $this->em->getRepository('Webit\Bundle\PriceComparatorCeneoBundle\Entity\Group')->findAll();
        $groupsByName = array();
        foreach($arGroups as $group) {
            $groupsByName[$group->getName()] = $group;
        }
        
        return $groupsByName;
    }
    
    private function showMsg($msg) {
        if($this->output) {
            $this->output->writeln($msg);
        }
    }
}