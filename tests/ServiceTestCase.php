<?php

namespace  App\Tests;

use App\Entity\TextMessage;
use App\Repository\TextMessageRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

/**
 *
 */
class ServiceTestCase extends WebTestCase
{
    /**
     * @return TextMessage[]
     */
    public function messagesDataProvider(): array
    {
        $message1 = new TextMessage();
        $message1->setUuid(Uuid::v6()->toRfc4122());
        $message1->setText("Test Message 1");
        $message1->setStatus('read');
        $message1->setCreatedAt(new \DateTime());

        $message2 = new TextMessage();
        $message2->setUuid(Uuid::v6()->toRfc4122());
        $message2->setText("Test Message 2");
        $message2->setStatus('sent');
        $message2->setCreatedAt(new \DateTime());

        return [$message1, $message2];
    }

    /**
     * @return void
     */
    public function loadTestData(): void
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        // Purge the test database before loading fixtures
        $purger = new ORMPurger($entityManager);
        $purger->purge();

        foreach ($this->messagesDataProvider() as $message) {
            $entityManager->persist($message);
        }

        $entityManager->flush();
    }
}