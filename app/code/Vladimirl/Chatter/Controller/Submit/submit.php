<?php
declare(strict_types=1);

namespace Vladimirl\Vladimirl_Chatter\Controller\Submit;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Submit extends \Magento\Framework\App\Action\Action
{   
	const STATUS_ERROR = 'Error';
	const STATUS_SUCCESS = 'Success';

	private $formKeyValidator;

	public function __constructor(
		\Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
		\Magento\Framework\App\Action\Context $context
	) {
		parent::__constructor($context);
		$this->formKeyValidator = $formKeyValidator;
	}
	public function execute ()
	{
		$request = $this->getRequest();

		try {
			if (!$this->formKeyValidator->validate($request)){
				throw new LocalizedException(__('Something went wrong'));
			}
			$data = [
				'status' => self::STATUS_SUCCESS,
				'message' => __('Your request was submitted')
			];
		} catch (LocalizedException $e) {
			$data = [
				'status' => self::STATUS_ERROR,
				'message' => 'Your request was NOT submitted'
			];
		}
		$controllerResult = $this->ResultFactory->create(ResultFactory::TYPE_JSON);
		return $controllerResult->setData($data);
	}
}