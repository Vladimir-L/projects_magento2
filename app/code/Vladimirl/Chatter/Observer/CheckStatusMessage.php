<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Observer;

class CheckStatusMessage implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http $request
     */
    protected $request;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * LayoutLoadBefore constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->request = $request;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $action = $this->request->getFullActionName();
        $this->customerSession->setPageAction($action);
    }
}
