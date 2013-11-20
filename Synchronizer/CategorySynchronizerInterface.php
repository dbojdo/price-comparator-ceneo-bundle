<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Synchronizer;

interface CategorySynchronizerInterface {
    /**
     * @return void
     */
    public function synchronizeCategories($mainOnly = false);
}
