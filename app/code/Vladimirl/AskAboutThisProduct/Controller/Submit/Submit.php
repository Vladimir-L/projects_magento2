<?php
declare(strict_types=1);

namespace Vladimirl\AskAboutThisProduct\Controller\Submit;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;

class Submit extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Vladimirl\AskAboutThisProduct\Model\Email
     */
    private $email;

    /**
     * Submit constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Vladimirl\AskAboutThisProduct\Model\Email $email
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Vladimirl\AskAboutThisProduct\Model\Email $email,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->email = $email;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $request = $this->getRequest()->getParams();
        $this->customerSession->setData($request);
        $this->email->send();
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => 'Email was successfully sent!',
        ]);
        return $response;
    }
}
