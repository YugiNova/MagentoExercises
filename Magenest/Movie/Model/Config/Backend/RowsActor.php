<?php
namespace Magenest\Movie\Model\Config\Backend;

use Magento\Framework\App\Config\Value as ConfigValue;
use Magento\Framework\Serialize\SerializerInterface;
use Magenest\Movie\Model\ActorFactory;

class RowsActor extends ConfigValue
{
    /**
     * Json Serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var actorFactory
     */
    protected $actorFactory;

    /**
     * @param SerializerInterface $serializer
     * @param ActorFactory $actorFactory
     */
    public function __construct(
        SerializerInterface $serializer,
        ActorFactory $actorFactory,
    ) {
        $this->serializer = $serializer;
        $this->actorFactory = $actorFactory;
    }
    /**
     * Process data after load
     *
     * @return void
     */
    protected function _afterLoad()
    {
        /** @var string $value */
        $actorRows = $this->actorFactory->create()->getCollection()->getSize();
        $this->setValue((string)$actorRows);
        $value = $this->getValue();

        if(isset($value)) {
            $decodedValue = $this->serializer->unserialize($value);
            $this->setValue($decodedValue);
        }
    }
}
