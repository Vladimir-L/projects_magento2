<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Submit;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;

class Submit extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    public function execute()
    {   
        $request = $this->getRequest()->getParam('textRequest');
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => 'Our administrator will contact you soon!',
            'textform' => $request
        ]);
        return $response;
    }
}
