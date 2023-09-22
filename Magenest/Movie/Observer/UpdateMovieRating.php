<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
class UpdateMovieRating implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $movie = $observer->getData();
        $debug = '';
        $movie->setData('rating',0)->save();
    }
}
