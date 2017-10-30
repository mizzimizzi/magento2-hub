<?php
namespace Contactlab\Hub\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;
use Contactlab\Hub\Api\EventManagementInterface;
use Contactlab\Hub\Model\Event\Strategy\WishlistAddProduct;

class WishlistProductAddAfter implements ObserverInterface
{
    protected $_customerSession;
    protected $_eventService;
    protected $_strategy;

    public function __construct(
        Session $customerSession,
        EventManagementInterface $eventService,
        WishlistAddProduct $strategy
    )
    {
        $this->_customerSession = $customerSession;
        $this->_eventService = $eventService;
        $this->_strategy = $strategy;
    }

    public function execute(Observer $observer)
    {
        foreach($observer->getItems() as $item)
        {
            $data = $item->getData();
            $data['email'] = $this->_customerSession->getCustomer()->getEmail();
            $this->_strategy->setContext($data);
            $this->_eventService->collectEvent($this->_strategy);
        }
    }
}