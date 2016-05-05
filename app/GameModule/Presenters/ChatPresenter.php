<?php

namespace Game\GameModule\Presenters;

use Game\Entities\Chat\ChatMessage;
use Game\Entities\Chat\ChatQuery;
use Teddy\Forms\Form;



class ChatPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this['addMessageForm']['userId']->setValue($this->user->getId());

		$query = (new ChatQuery())->orderByPostedAt();
		$posts = $this->em->fetch($query);
		$posts->applyPaging(0, 500);
		$this->template->posts = $posts;
	}



	public function createComponentAddMessageForm()
	{
		$form = new Form();
		$form->addHidden('userId');
		$form->addText('message', 'Message');
		$form->addSubmit('send', 'Send');
		return $form->setBootstrapRenderer();
	}



	public function handleDeleteMsg($id)
	{
		/** @var ChatMessage $chatMessage */
		$chatMessage = $this->em->find(ChatMessage::class, $id);
		if (!$chatMessage || !$chatMessage->canDelete($this->user)) {
			$this->error();
		}

		$this->em->remove($chatMessage)->flush();
		$this->successFlashMessage('Message deleted');
		$this->refreshPage();
	}

}
