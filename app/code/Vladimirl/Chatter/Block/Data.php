<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Block;

class Data extends \Magento\Framework\View\Element\Template
{
    public const XML_PATH_ALLOW_FOR_GUESTS = 'vladimirl_chatter/general/allow_for_guests';

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;

    /**
     * Data constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function checkLoggedIn(): bool
    {
        $allowUseChatter = true;
        if (!$this->customerSession->isLoggedIn() && !$this->scopeConfig->getValue(self::XML_PATH_ALLOW_FOR_GUESTS)
        ) {
            $allowUseChatter = false;
        }
        return $allowUseChatter;
    }
}
