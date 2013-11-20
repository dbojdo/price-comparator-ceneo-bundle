<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository {
    public function persist(Category $category) {
        $this->getEntityManager()->persist($category);
    }
}