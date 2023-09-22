<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magenest\Movie\Model\MovieFactory;

class Save extends \Magento\Backend\App\Action
{
    protected $resultRedirectFactory;
    protected $scopeConfig;
    protected $context;
    protected $movieFactory;
    protected $resource;

    public function __construct(
        Context              $context,
        RedirectFactory      $resultRedirectFactory,
        ScopeConfigInterface $scopeConfig,
        MovieFactory         $movieFactory,
        ResourceConnection   $resource
    )
    {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->movieFactory = $movieFactory;
        $this->resource = $resource;
    }

    public function execute()
    {
        $resultPage = $this->resultRedirectFactory->create();
        try
        {
            $data = $this->getRequest()->getPostValue();
            $movieId = $data['movie_id'] ?? '';
            if($movieId)
            {
                $this->updateMovie($data);
                $this->messageManager->addSuccessMessage(__("Movie Edit Successfully"));
            }
            else
            {
                $this->createMovie($data);
                $this->messageManager->addSuccessMessage(__("New Movie Add Successfully"));
            }
        }catch (\Exception $e)
        {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $debug = '';
        return $resultPage->setPath('*/*/index');
    }

    /**
     * @param $data
     * @return void
     */
    public function createMovie($data)
    {
        $movie = $this->movieFactory->create();
        $newMovie = $movie->addData([
            'name' => $data['name'],
            'description' => $data['description'],
            'rating' => $data['rating'],
            'director_id' => $data['director_id']
        ])->save();

        $actorData = [];
        foreach ($data['actor_id'] as $actor_id)
        {
            $actorData[] = [
                'movie_id' => $newMovie->getId(),
                'actor_id' => $actor_id
            ];
        }
        $connection = $this->resource->getConnection();
        $connection->insertMultiple(
            'magenest_movie_actor',
            $actorData
        );
    }

    /**
     * @param $data
     * @return void
     */
    public function updateMovie($data)
    {
        $movie = $this->movieFactory->create();
        $updateMovie = $movie->load($data['movie_id'])
                            ->setData('name',$data['name'])
            ->setData('description',$data['description'])
            ->setData('rating',$data['rating'])
            ->setData('director_id'.$data['director_id'])
            ->save();
        $actorData = [];
        foreach ($data['actor_id'] as $actor_id)
        {
            $actorData[] = [
                'movie_id' => $data['movie_id'],
                'actor_id' => $actor_id
            ];
        }
        $connection = $this->resource->getConnection();
        $connection->delete(
            'magenest_movie_actor',
            ['movie_id = '.$data['movie_id']]
        );
        $connection->insertMultiple(
            'magenest_movie_actor',
            $actorData
        );
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Movie::movie_form');
    }
}
