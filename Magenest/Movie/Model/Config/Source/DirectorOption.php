<?php

namespace Magenest\Movie\Model\Config\Source;


class DirectorOption implements \Magento\Framework\Option\ArrayInterface
{
    private $directorFactory;

    public function __construct(\Magenest\Movie\Model\DirectorFactory $directorFactory)
    {
        $this->directorFactory = $directorFactory;
    }

    /**
     * Retrieve Custom Option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $directors = $this->directorFactory->create()->getCollection()->getData();
        $options = [];
        foreach($directors as $director)
        {
            $options[] = ['value' => $director['director_id'], 'label' => $director['name']];
        }
        return $options;
    }
}
