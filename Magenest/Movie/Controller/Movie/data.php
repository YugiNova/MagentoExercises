<?php

namespace Magenest\Movie\Controller\Movie;
class  Data extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    private $movieFactory;
    private $directorFactory;
    private $curl;
    private const ACTORS_API_URL = 'https://6401dc9d0a2a1afebef3c167.mockapi.io/actors';
    private const MOVIES_API_URL = 'https://6401dc9d0a2a1afebef3c167.mockapi.io/movies';

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param \Magento\Framework\Serialize\Serializer\Json $seiralizer
     */
    public function __construct(
        \Magento\Framework\App\Action\Context      $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\HTTP\Client\Curl        $curl,
        \Magenest\Movie\Model\MovieFactory         $movieFactory,
        \Magenest\Movie\Model\DirectorFactory      $directorFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->movieFactory = $movieFactory;
        $this->directorFactory = $directorFactory;
        $this->curl = $curl;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $this->curl->get(self::ACTORS_API_URL);
        $movies = json_decode($this->curl->getBody());

        $debug = '';

        return $resultPage;
    }
}
