<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Synchronizer;

use Webit\PriceComparator\Ceneo\Provider\CategoryProviderInterface;
use Webit\Bundle\PriceComparatorCeneoBundle\Entity\CategoryRepository;
use Webit\Bundle\PriceComparatorCeneoBundle\Entity\Category;
use Symfony\Component\Console\Output\OutputInterface;
use Webit\PriceComparator\Ceneo\Model\Category as BaseCeneoCategory;

use Doctrine\ORM\EntityManager;

class CategorySynchronizer implements CategorySynchronizerInterface {
    /**
     * 
     * @var CategoryProviderInterface
     */
    protected $categoryProvider;
    
    /**
     * 
     * @var CategoryRepository
     */
    protected $categoryRepository;
    
    /**
     * 
     * @var Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    
    /**
     * 
     * @param CategoryProviderInterface $categoryProvider
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryProviderInterface $categoryProvider, EntityManager $em) {
        $this->categoryProvider = $categoryProvider;
        $this->em = $em;
        $this->categoryRepository = $this->em->getRepository('Vela\Ec\PriceComparatorBundle\Entity\Ceneo\Category');
    }
    
    /**
     * 
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output) {
        $this->output = $output;
    }
    
    public function synchronizeCategories($mainOnly = false) {
        if($mainOnly) {
            $this->showMsg('Synchronize main categories.');
        } else {
            $this->showMsg('Synchronize enabled categories.');
        }
        
        $mainCategories = $this->getCategoriesByCeneoId(true);
        if($mainOnly == false && count($mainCategories) == 0) {
            $this->showMsg('No main categories found.');
            $this->synchronizeCategories(true);
            $mainCategories = $this->getCategoriesByCeneoId(true);
        }
        $this->showMsg(sprintf('Found %d main categories',count($mainCategories)));
        
        $categoriesByCeneoId = $mainOnly ? $mainCategories : $this->getCategoriesByCeneoId();
        
        $currentCategoriesByCeneoId = array();
        $categories = $this->categoryProvider->getCategories();
        foreach($categories as $category) {
            $this->persistCategory($category, $mainOnly == false, $categoriesByCeneoId, $currentCategoriesByCeneoId, $currentCategoriesByCeneoId);
        }
        
        
        $remove = array_diff(array_keys($categoriesByCeneoId), array_keys($currentCategoriesByCeneoId));
        $skipped = array();
        foreach($remove as $c) {
            if($categoriesByCeneoId[$c]->getParent() == null) {
                $skipped[] = $categoriesByCeneoId[$c];
            } else {
                $this->showMsg(sprintf('Remove category: CeneoId: %d, Path: %s', $c, $categoriesByCeneoId[$c]->getPath()));
            }
        }
        $this->showMsg(sprintf('Successfuly synchronized %d categories. %d removed.',count($currentCategoriesByCeneoId), count($remove) - count($skipped)));
        $this->em->flush();
        
        $this->categoryRepository->recover();
    }
    
    private function getCategoriesByCeneoId($mainOnly = false) {
        if($mainOnly) {
            $categories = $this->categoryRepository->findByTreeLevel(0);
        } else {
            $categories = $this->categoryRepository->findAll();
        }
        
        
        $categoriesByCeneoId = array();
        foreach($categories as $category) {
            $categoriesByCeneoId[$category->getCeneoId()] = $category;
        }
        
        return $categoriesByCeneoId;
    }
    
    private function persistCategory(BaseCeneoCategory $baseCategory, $persistChildren, array &$categoriesByCeneoId, array &$currentCategoriesByCeneoId) {
        $eCategory = $this->mapCategory($baseCategory, $categoriesByCeneoId);
        if($eCategory->getParent() == null && $eCategory->getEnabled() == false) {
            $this->showMsg(sprintf('Disabled category %s has been skipped.',$eCategory->getName()));
            return null;
        }
        
        $categoriesByCeneoId[$eCategory->getCeneoId()] = $eCategory;
        $currentCategoriesByCeneoId[$eCategory->getCeneoId()] = $eCategory;
        
        $this->showMsg(sprintf('Persists category: CeneoId: %d, Path: %s',$eCategory->getCeneoId(), $eCategory->getPath()));
        if($eCategory->getParent() == null) {
            $this->categoryRepository->persistAsLastChild($eCategory);
        } else {
            if($eCategory->getId() == null) {
                $this->categoryRepository->persistAsLastChildOf($eCategory, $eCategory->getParent());
            } else {
                $this->categoryRepository->moveDown($eCategory);
            }
        }
        
        if($persistChildren) {
            foreach($baseCategory->getSubcategories() as $subcategory) {
                $this->persistCategory($subcategory, true, $categoriesByCeneoId, $currentCategoriesByCeneoId);
            }
        }
        return $eCategory;
    }
    
    private function mapCategory(BaseCeneoCategory $baseCategory, array $categoriesByCeneoId) {
        $eParent = $baseCategory->getParent() == null ? null : $categoriesByCeneoId[$baseCategory->getParent()->getCeneoId()];
        $eCategory = key_exists($baseCategory->getCeneoId(), $categoriesByCeneoId) ? $categoriesByCeneoId[$baseCategory->getCeneoId()] : new Category();
            $eCategory->setName($baseCategory->getName());
            $eCategory->setCeneoId($baseCategory->getCeneoId());
            if($eParent) {
                $eCategory->setParent($eParent);
            }
        
            if($eCategory->getId() == null) {
                $eCategory->setEnabled(false);
            }
        return $eCategory;
    }
    
    private function showMsg($msg) {
        if($this->output) {
            $this->output->writeln($msg);
        }
    }
}
