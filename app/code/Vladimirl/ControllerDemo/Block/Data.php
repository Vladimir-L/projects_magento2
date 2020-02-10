<?php
declare(strict_types=1);

namespace Vladimirl\ControllerDemo\Block;

class Data extends \Magento\Framework\View\Element\Template
{
	public function getFullName ()
    {
        $firstName = $this->getRequest()->getParam('firstName');
        $lastName = $this->getRequest()->getParam('lastName');
        $fullName = $firstName . ' ' . $lastName;
        return $fullName;
    }

    public function getNameRepository ()
    {
        return $this->getRequest()->getParam('nameRepository');
    }

    public function getUrlRepository ()
    {
        return $this->getRequest()->getParam('urlRepository');
    }    
}
