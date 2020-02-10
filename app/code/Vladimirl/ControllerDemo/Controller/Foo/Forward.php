<?php
declare(strict_types=1);

namespace Vladimirl\ControllerDemo\Controller\Foo;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Forward as ForwardResult;

class Forward extends \Magento\Framework\App\Action\Action implements
	\Magento\Framework\App\Action\HttpGetActionInterface 
{
	/**
	 * @inheritDoc
	 * 	https://vladimir-lopatkin.local/controller-demo-url/foo/forward/
	 */
	public function execute()
	{
		$resultRedirect = $this->resultRedirectFactory->create();
    	$params = [
    		'firstName' => 'Vladimir',
    		'lastName' => 'Lopatkin',
    		'nameRepository' => 'Project_Magento_2',
    		'urlRepository' => 'https://github.com/Vladimir-L/projects_magento2'
		];

    	$resultRedirect->setPath('*/*/data', array('_query'=> $params));

    	return $resultRedirect;
    }
}
