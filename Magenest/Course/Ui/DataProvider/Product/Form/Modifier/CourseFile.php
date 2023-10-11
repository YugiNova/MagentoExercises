<?php

namespace Magenest\Course\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\ResourceConnection;
use Magenest\Course\Model\Uploader;


class CourseFile extends AbstractModifier
{

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    protected $locator;

    protected $resource;

    protected $uploader;


    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager       $arrayManager,
        LocatorInterface   $locator,
        ResourceConnection $resource,
        Uploader           $uploader,
    )
    {
        $this->arrayManager = $arrayManager;
        $this->locator = $locator;
        $this->resource = $resource;
        $this->uploader = $uploader;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $model = $this->locator->getProduct();
        $data[$model->getId()]['product']['course-files']['dynamic_row'] = $this->getCourseFile($model->getId());;
        return $data;
    }

    public function getCourseFile($productId)
    {
        $connection = $this->resource->getConnection();
        $courseFiles = $connection->query('select * from course_files where product_id = ?', [$productId])->fetchAll();
        $updatedCourseFiles = [];
        foreach ($courseFiles as $courseFile) {
            if ($courseFile['file_type'] == 'file') {
                $updatedCourseFiles[] = [
                    'file_label' => $courseFile['file_label'],
                    'file_type' => $courseFile['file_type'],
                    'file_url' => [
                        [
                            'file' => $courseFile['file_url'],
                            'url' => 'http://local.magento.com/pub/media/' . $courseFile['file_url'],
                            'name' => ltrim($courseFile['file_url'], 'faq/icon')
                        ]
                    ],
                ];
            } else {

                $updatedCourseFiles[] = [
                    'file_label' => $courseFile['file_label'],
                    'file_type' => $courseFile['file_type'],
                    'file_link' => $courseFile['file_url'],
                ];
            }
        }

        $debug = '';
        return $updatedCourseFiles;
    }

}
