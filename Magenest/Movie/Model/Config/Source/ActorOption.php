<?php

namespace Magenest\Movie\Model\Config\Source;


class ActorOption implements \Magento\Framework\Option\ArrayInterface
{
    private $actorFactory;

    public function __construct(\Magenest\Movie\Model\ActorFactory $actorFactory)
    {
        $this->actorFactory = $actorFactory;
    }

    /**
     * Retrieve Custom Option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $directors = $this->actorFactory->create()->getCollection()->getData();
        $options = [];
        foreach($directors as $director)
        {
            $options[] = ['value' => $director['actor_id'], 'label' => $director['name']];
        }
        return $options;
    }
}
