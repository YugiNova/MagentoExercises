<?php

namespace Magenest\Course\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection;
use Magenest\Course\Model\Uploader;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class SaveProduct implements ObserverInterface
{
    private $resource;
    private $uploader;

    protected $directoryList;

    protected $fileDriver;

    public function __construct(
        ResourceConnection $resource,
        Uploader           $uploader,
        DirectoryList      $directoryList,
        File               $fileDriver
    )
    {
        $this->directoryList = $directoryList;
        $this->fileDriver = $fileDriver;
        $this->resource = $resource;
        $this->uploader = $uploader;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $productData = $observer->getData('product')->getData();
        $isNew = $observer->getData('product')->isObjectNew();
        $documents = $productData['course-files'];

        if($isNew)
        {
            $this->createDocuments($documents,$productData);
        }
        else
        {
            $this->updateDocuments($documents,$productData);
        }

        $debug = '';
    }

    public function createDocuments($documents,$productData)
    {
        if (isset($documents['dynamic_row'])) {
            $documents = $documents['dynamic_row'];
            foreach ($documents as $document) {
                if ($document['file_type'] == 'file') {
                    $this->createDocumentFile($document, $productData['entity_id']);
                } else {
                    $this->createDocumentLink($document, $productData['entity_id']);
                }
            }
        }
    }

    public function updateDocuments($documents,$productData)
    {
        $rows = isset($documents['dynamic_row']);
        if (isset($documents['dynamic_row'])) {
            $this->deleteDocuments($productData['entity_id']);
            $documents = $documents['dynamic_row'];
            foreach ($documents as $document) {
                if ($document['file_type'] == 'file') {
                    $this->updateDocumentFile($document,$productData['entity_id']);
                } else {
                    $this->createDocumentLink($document, $productData['entity_id']);
                }
            }
        }
        else{
            $this->deleteDocuments($productData['entity_id']);
        }
    }

    public function deleteDocuments($productId)
    {
        $connection = $this->resource->getConnection();
        $connection->delete('course_files','product_id = '.$productId);
    }
    public function updateDocumentFile($document, $productId)
    {
        try {
            $fileName = $document['file_url'][0]['name'];
            $filePath = $this->uploader->getFilePath($this->uploader->getBasePath(),$fileName);
            $path = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) . '/' . $filePath;
            $content = $this->fileDriver->isFile($path);

            if($content)
            {
                $connection = $this->resource->getConnection();
                $tableName = $connection->getTableName('course_files');
                $connection->insert($tableName, [
                    'product_id' => $productId,
                    'file_label' => $document['file_label'],
                    'file_url' => $filePath,
                    'file_type' => $document['file_type']
                ]);
            }
            else
            {
                $this->createDocumentFile($document,$productId);
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * Create a new document file and insert a new record into database
     * @param $document
     * @param $productId
     * @return void
     * @throws \Exception
     */
    public function createDocumentFile($document, $productId)
    {
        try {
            $fileName = $document['file_url'][0]['name'];
            $this->uploader->moveFileFromTmp($fileName);
            $filePath = $this->uploader->getFilePath($this->uploader->getBasePath(), $fileName);

            $connection = $this->resource->getConnection();
            $tableName = $connection->getTableName('course_files');
            $connection->insert($tableName, [
                'product_id' => $productId,
                'file_label' => $document['file_label'],
                'file_url' => $filePath,
                'file_type' => $document['file_type']
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }


    /**
     * Insert a new url info record into database
     * @param $document
     * @param $productId
     * @return void
     */
    public function createDocumentLink($document, $productId)
    {
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName('course_files');
        $connection->insert($tableName, [
            'product_id' => $productId,
            'file_label' => $document['file_label'],
            'file_url' => $document['file_link'],
            'file_type' => $document['file_type']
        ]);
    }
}
