<?php
declare(strict_types=1);

namespace Vladimirl\AskAboutThisProduct\Controller\Submit;

use Magento\Framework\Controller\ResultFactory;

class Submit extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Magento\Captcha\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Captcha\Observer\CaptchaStringResolver
     */
    protected $captchaStringResolver;

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
     * @param \Magento\Captcha\Helper\Data $helper
     * @param \Magento\Captcha\Observer\CaptchaStringResolver $captchaStringResolver
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Vladimirl\AskAboutThisProduct\Model\Email $email
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Captcha\Helper\Data $helper,
        \Magento\Captcha\Observer\CaptchaStringResolver $captchaStringResolver,
        \Magento\Customer\Model\Session $customerSession,
        \Vladimirl\AskAboutThisProduct\Model\Email $email,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->_helper = $helper;
        $this->captchaStringResolver = $captchaStringResolver;
        $this->customerSession = $customerSession;
        $this->email = $email;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $request = $this->getRequest()->getParams();
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $formId = 'email_form_captcha';
        $captcha = $this->_helper->getCaptcha($formId);
        if ($captcha->isRequired()) {
            if (!$captcha->isCorrect($this->captchaStringResolver->resolve($this->getRequest(), $formId))) {
                $response->setData([
                    'message' => 'Error! Incorrect CAPTCHA',
                ]);
            } else {
                $this->customerSession->setData($request);
                $this->email->send();
                $response->setData([
                    'message' => 'Email was successfully sent!',
                ]);
            }
        }
        return $response;
    }
}
