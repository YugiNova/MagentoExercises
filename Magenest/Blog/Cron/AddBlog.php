<?php
namespace Magenest\Blog\Cron;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * custom cron actions
 */
class AddBlog
{
    protected $_logger;

    protected $timezoneInterface;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        TimezoneInterface $timezoneInterface
    )
    {
        $this->_logger = $logger;
        $this->timezoneInterface = $timezoneInterface;
    }
    public function execute()
    {
        // do your custom code as your requirement
        $formatDate = $this->timezoneInterface->formatDate();
        // you can also get format wise date and time
        $dateTime = $this->timezoneInterface->date()->format('Y-m-d H:i:s');
        $date = $this->timezoneInterface->date()->format('Y-m-d');
        $time = $this->timezoneInterface->date()->format('H:i');
        $this->_logger->debug('blog cronjob excuted at '.$dateTime);
    }
}
