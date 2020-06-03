<?php

namespace Vladimirl\AskAboutThisProduct\Model;

use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;

class Email
{
    public const XML_PATH_SENDER = 'contact/email/sender_email_identity';

    public const XML_PATH_RECIPIENT = 'contact/email/recipient_email';

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     */
    private $inlineTranslation;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * Email constructor.
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function send(): void
    {
        $dataCustomer = $this->customerSession;

        $templateVariables = [
            'name' => $dataCustomer->getData('fname'),
            'email' => $dataCustomer->getData('email'),
            'question' => $dataCustomer->getData('textQuestion'),
            'sku' => $dataCustomer->getData('sku')
        ];

        $this->inlineTranslation->suspend();

        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('vladimirl_email_ask_about')
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($templateVariables)
                ->setFromByScope($this->scopeConfig->getValue(
                    self::XML_PATH_SENDER,
                    ScopeInterface::SCOPE_STORE
                ))
                ->addTo($this->scopeConfig->getValue(
                    self::XML_PATH_RECIPIENT,
                    ScopeInterface::SCOPE_STORE
                ))
                ->setReplyTo('volodymyrl@default-value.com', 'Volodymyr Lopatkin')
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
