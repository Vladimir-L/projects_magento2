<?php
declare(strict_types=1);

namespace Vladimirl\AskAboutThisProduct\Block;

class Data extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Block\Product\View
     */
    protected $productRepository;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * Data constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Block\Product\View $productRepository
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Block\Product\View $productRepository,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getProductSku(): string
    {
        return $this->productRepository->getProduct()->getSku();
    }

    /**
     * @return string
     */
    public function getNameCustomer(): string
    {
        if ($this->customerSession->isLoggedIn()) {
            $dataCustomer = $this->customerSession->getCustomerData();
            $name = $dataCustomer->getFirstname() . ' ' . $dataCustomer->getLastname();
        } else {
            $name = '';
        }
        return $name;
    }

    /**
     * @return string
     */
    public function getEmailCustomer(): string
    {
        if ($this->customerSession->isLoggedIn()) {
            $email = $this->customerSession->getCustomerData()->getEmail();
        } else {
            $email = '';
        }
        return $email;
    }
}
