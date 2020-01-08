<?php
declare(strict_types=1);

namespace Vladimirl\ControllerDemo\Controller\Foo;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Data extends \Magento\Framework\App\Action\Action implements
	\Magento\Framework\App\Action\HttpGetActionInterface
{	
    public function execute()
    {
    	return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}

