<?php

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;
use Magenest\Movie\Model\MovieFactory;

class MovieList extends Template
{
    private $movieFactory;

    public function __construct(
        Template\Context $context,
        MovieFactory     $movieFactory,
        array            $data = [])
    {
        parent::__construct($context, $data);
        $this->movieFactory = $movieFactory;
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getMovies()
    {
        $movies = $this->movieFactory->create()->getCollection();
        return $movies;
    }

}
