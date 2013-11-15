<?php
namespace Webit\Bundle\PriceComparatorCoreBundle\Exposer;

use Webit\Bundle\PriceComparatorCoreBundle\Exposer\OfferExposerInterface;
use Webit\Bundle\PriceComparatorCoreBundle\Writer\OfferWriterInterface;
use Webit\PriceComparator\Ceneo\Provider\OfferProviderInterface;

class CeneoOfferExposer implements OfferExposerInterface {
    
    /**
     *
     * @var OfferProviderInterface
     */
    protected $offerProvider;
    
    /**
     * 
     * @var OfferWriterInterface
     */
    protected $writer;

    /**
     *
     * @var string
     */
    protected $targetDir;
    
    /**
     * 
     * @var int
     */
    protected $generationInterval;
    
    public function __construct(OfferProviderInterface $offerProvider, OfferWriterInterface $writer, $targetDir, $generationInterval = 1) {
        $this->offerProvider = $offerProvider;
        $this->writer = $writer;
        $this->targetDir = $targetDir;
        $this->generationInterval = $generationInterval;
    }
    
    /**
     * 
     * @param string $format
     * @return \SplFileInfo
     */
    public function getOffersFile($refresh = false, $format = 'xml') {
        $file = new \SplFileInfo($this->targetDir.'/ceneo.'.$format);
        if($this->needsGenerate($refresh, $file)) {
            $offers = $this->offerProvider->getOffers();
            $this->writer->writeOffers($offers, $file);
        }
        
        return $this->targetFile;
    }
    
    /**
     * @return array
     */
    public function getSupportedFormats() {
        return array('xml');
    }
    
    private function needsGenerate($forceGeneratrion, \SplFileInfo $file) {
        if($forceGeneratrion || $file->isFile() == false) {
            return true;
        }

        $ctime = \DateTime::createFromFormat('U', $file->getMTime());
        $expirationTime = $ctime->add(new \DateInterval('P'.$this->generationInterval .'D'));

        return new \DateTime() > $expirationTime;
    }
}
