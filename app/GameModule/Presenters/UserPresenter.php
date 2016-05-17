<?php

namespace Game\GameModule\Presenters;

use Game\GameModule\Components\IWebcamControlFactory;
use Game\GameModule\Components\WebcamControl;
use Nette\Utils\ArrayHash;
use Teddy\Forms\Form;



class UserPresenter extends \Teddy\GameModule\Presenters\UserPresenter
{

	/**
	 * @var IWebcamControlFactory
	 * @inject
	 */
	public $webcamControlFactory;



	public function createComponentWebcam()
	{
		$control = $this->webcamControlFactory->create();
		$control->onImageCreated = function (WebcamControl $webcamControl, $filename) {
			$this->user->setAvatar($filename);
			$this->em->flush();
			$this->successFlashMessage('Avatar updated');
			$this->redirect('detail');
		};
		return $control;
	}

}
