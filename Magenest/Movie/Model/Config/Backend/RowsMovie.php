<?php
namespace Magenest\Movie\Model\Config\Backend;

use Magento\Framework\App\Config\Value as ConfigValue;
use Magento\Framework\Serialize\SerializerInterface;
use Magenest\Movie\Model\MovieFactory;

class RowsMovie extends ConfigValue
{
    /**
     * Json Serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var movieFactory
     */
    protected $movieFactory;

    /**
     * @param SerializerInterface $serializer
     * @param MovieFactory $movieFactory
     */
    public function __construct(
        SerializerInterface $serializer,
        MovieFactory $movieFactory,
    ) {
        $this->serializer = $serializer;
        $this->movieFactory = $movieFactory;
    }
    /**
     * Process data after load
     *
     * @return void
     */
    protected function _afterLoad()
    {
        /** @var string $value */
        $movieRows = $this->movieFactory->create()->getCollection()->getSize();
        $this->setValue((string)$movieRows);
        $value = $this->getValue();
        $debug = '';
        if(isset($value)) {
            $decodedValue = $this->serializer->unserialize($value);
            $this->setValue($decodedValue);
        }
    }
}
