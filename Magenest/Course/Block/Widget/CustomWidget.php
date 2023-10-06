<?php
namespace Magenest\Course\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magenest\Movie\Model\MovieFactory;

class CustomWidget extends Template implements BlockInterface
{
    protected $_template = "custom_widget.phtml";
    protected $movieFactory;

    public function __construct (
        Template\Context $context,
        MovieFactory $movieFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->movieFactory = $movieFactory;
    }

    public function getMovieList()
    {
        $movies = $this->movieFactory->create()->getCollection()
            ->setPageSize($this->getData('movie_count'))->getData();

        return $movies;
    }
}
