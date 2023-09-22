<?php
namespace Magenest\Movie\Observer;
use Magento\Framework\Event\ObserverInterface;
class UpdateTextField implements ObserverInterface
{
    private $scopeConfig;
    private $configWritter;
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWritter
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->configWritter = $configWritter;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $configValue = $this->scopeConfig->getValue(
            'movie/movie_config/text_field',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($configValue == 'Ping')
        {
            $this->configWritter->save('movie/movie_config/text_field','Pong');
        }
        $debug = '';
    }
}
