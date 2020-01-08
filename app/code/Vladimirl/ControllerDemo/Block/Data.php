<?php
namespace Vladimirl\ControllerDemo\Block;
class Data extends \Magento\Framework\View\Element\Template
{
	public function getFullName()
    {
        $firstName = $this->getRequest()->getParam('firstName');
        $lastName = $this->getRequest()->getParam('lastName');
        $fullName = $firstName . ' ' . $lastName;
        return $fullName;
    }
    public function getUrlRepository()
    {
        $repository = $this->getRequest()->getParam('repository');
        return $repository;
    }    
}