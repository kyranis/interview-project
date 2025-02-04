<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\TextMessage;
use App\Repository\TextMessageRepository;
use App\Tests\ServiceTestCase;

class MessageRepositoryTest extends ServiceTestCase
{
    /**
     * @var TextMessageRepository $textMessageRepository
     */
    private TextMessageRepository $textMessageRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->textMessageRepository = self::getContainer()->get(TextMessageRepository::class); // @phpstan-ignore-line
    }
    function test_findByStatus_with_param(): void
    {
        $this->loadTestData();

        $result = $this->textMessageRepository->findByStatus('read');

        $this->assertCount(1, $result);
        $this->assertEquals('read', $result[0]->getStatus());
    }

    function test_findByStatus_without_param(): void
    {
        $this->loadTestData();

        $result = $this->textMessageRepository->findByStatus('');

        $this->assertCount(2, $result);
        $this->assertSame($result, $this->textMessageRepository->findAll());
    }

    function test_create(): void
    {
        $this->loadTestData();

        $this->assertCount(2, $this->textMessageRepository->findAll());

        $newMessageId = $this->textMessageRepository->create('New Test Message');

        $this->assertCount(3, $this->textMessageRepository->findAll());

        $this->assertInstanceOf(TextMessage::class, $this->textMessageRepository->find($newMessageId));

        $this->assertEquals('New Test Message', $this->textMessageRepository->find($newMessageId)->getText());
    }
}