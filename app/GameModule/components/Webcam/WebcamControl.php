<?php

namespace Game\GameModule\Components;

use Nette\Utils\ArrayHash;
use Teddy;
use Nette\Application\UI\Control;
use Teddy\Forms\Form;
use Teddy\Security\User;
use Teddy\Services\ImageService;



/**
 * @package Game\GameModule\Components
 *
 * @method void onImageCreated(WebcamControl $this, string $filename)
 */
class WebcamControl extends Control
{

	/**
	 * @var array
	 */
	public $onImageCreated = [];

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var ImageService
	 */
	private $imageService;



	/**
	 * @param User $user
	 * @param ImageService $imageService
	 */
	public function __construct(User $user, ImageService $imageService)
	{
		parent::__construct();
		$this->user = $user;
		$this->imageService = $imageService;
	}



	public function render()
	{
		$template = parent::createTemplate();
		$template->setFile(__DIR__ . '/webcam.latte');
		$template->render();
	}



	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = new Form();
		$form->addTextarea('asciiImg');
		$form->addTextarea('imgBase64');
		$form->addCheckbox('ascii', 'ASCII');
		$form->addButton('togglePause', 'Pause / Unpause');
		$form->addButton('capture', 'Capture');
		$form->onSuccess[] = $this->captureFormSuccess;
		return $form;
	}



	public function captureFormSuccess(Form $form, ArrayHash $values)
	{
		if ($values->ascii) {
			$filename = $this->saveAscii($values->asciiImg);

		} else {
			$filename = $this->user->getId() . '-ascii.png';

			$img = $values->imgBase64;
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			file_put_contents($this->imageService->getAvatarPath($filename), $img);
		}

		$this->onImageCreated($this, $filename);
	}



	/**
	 * Takes ASCII text and saves it to img
	 *
	 * @param string $text
	 * @return string
	 */
	private function saveAscii($text)
	{
		$filename = $this->user->getId() . '-ascii.png';
		$lines = explode("\n", $text);

		$im = imagecreate(1600, 1200);
		$bg = imagecolorallocate($im, 255, 255, 255);
		$textcolor = imagecolorallocate($im, 0, 0, 000);

		for ($i = 0; $i < count($lines); $i++) {
			imagestring($im, 1, 0, $i * 10, $lines[$i], $textcolor);
		}

		imagepng($im, $this->imageService->getAvatarPath($filename));
		imagedestroy($im);
		return $filename;
	}

}



interface IWebcamControlFactory
{

	/**
	 * @return WebcamControl
	 */
	public function create();
}
