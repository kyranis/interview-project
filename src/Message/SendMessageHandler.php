<?php
declare(strict_types=1);

namespace App\Message;

use App\Repository\TextMessageRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
/**
 * Added TextMessageRepository as dependency and use it to handle message creation, for more simplicity and better readability
 */
class SendMessageHandler
{
    private TextMessageRepository $textMessageRepository;

    public function __construct(TextMessageRepository $textMessageRepository)
    {
        $this->textMessageRepository = $textMessageRepository;
    }
    
    public function __invoke(SendMessage $sendMessage): void
    {
        $this->textMessageRepository->create($sendMessage->getText());
    }
}