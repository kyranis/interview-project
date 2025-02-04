<?php
declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\SendMessage;
use App\Message\SendMessageHandler;
use App\Repository\TextMessageRepository;
use App\Tests\ServiceTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SendMessageHandlerTest extends ServiceTestCase
{
    /**
     * @var TextMessageRepository
     */
    private TextMessageRepository $textMessageRepository;

    private SendMessageHandler $sendMessageHandler;
    public function setUp(): void
    {
        parent::setUp();

        $this->textMessageRepository = self::getContainer()->get(TextMessageRepository::class); // @phpstan-ignore-line
        $this->sendMessageHandler = new SendMessageHandler($this->textMessageRepository); // @phpstan-ignore-line
    }
    function test_invoke_handler(): void
    {
        $this->loadTestData();
        $this->assertCount(2, $this->textMessageRepository->findAll());

        $text = 'New Test Message';
        $sendMessage = new SendMessage($text);

        $this->sendMessageHandler->__invoke($sendMessage);

        $this->assertCount(3, $this->textMessageRepository->findAll());
    }
}