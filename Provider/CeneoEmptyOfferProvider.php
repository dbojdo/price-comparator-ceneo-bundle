<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Exposer;

use Webit\PriceComparator\Ceneo\Provider\OfferProviderInterface;
use Webit\PriceComparator\Ceneo\Model\OffersDocument;

class CeneoEmptyOfferProvider implements OfferProviderInterface {
    /**
     * @return OffersDocument
     */
    public function getOffers() {
        return new OffersDocument();
    }
}
