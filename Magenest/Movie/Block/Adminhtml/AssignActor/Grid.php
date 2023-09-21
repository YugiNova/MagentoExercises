<?php

namespace Magenest\Movie\Block\Adminhtml\AssignActor;

use Magento\Backend\Block\Widget\Grid as WidgetGrid;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $actorCollection;
    protected $actorFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magenest\Movie\Model\ResourceModel\Actor\Collection $actorCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                     $context,
        \Magento\Backend\Helper\Data                                $backendHelper,
        \Magenest\Movie\Model\ResourceModel\Actor\Collection        $actorCollection,
        \Magenest\Movie\Model\ActorFactory $actorFactory,
        array                                                       $data = []
    )
    {
        $this->actorCollection = $actorCollection;
        $this->actorFactory = $actorFactory;
        parent::__construct($context, $backendHelper, $data);
        $this->setEmptyText(__('No Actor Found'));
    }

    /**
     * Initialize the subscription collection
     *
     * @return WidgetGrid
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->actorCollection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'ids',
            [
                'type' => 'checkbox',
                'name' => 'in_banner',
                'values' => $this->_getSelectedActor(),
                'index' => 'actor_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );
//        $this->addColumn(
//            'actor_id',
//            [
//                'header' => __('ID'),
//                'index' => 'actor_id',
//            ]
//        );
        $this->addColumn(
            'actor_name',
            [
                'header' => __('Actor Name'),
                'index' => 'name',
            ]
        );
        return $this;
    }

    public function _getSelectedActor()
    {
        $movieId = $this->getRequest()->getParam('id');
        $sql = $this->actorFactory->create()->getCollection()
            ->addFieldToSelect('actor_id')
            ->addFieldToSelect('name')
            ->addFieldToFilter('magenest_movie_actor.movie_id', $movieId)
            ->join(
                'magenest_movie_actor',
                'main_table.actor_id = magenest_movie_actor.actor_id',
                '*'
            )
            ->getData();

        $data = [];
        foreach($sql as $item)
        {
            $data[] = $item['actor_id'];
        }
        $debug = '';
        return $data;
    }
}
