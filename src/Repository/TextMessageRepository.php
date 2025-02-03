<?php

namespace App\Repository;

use App\Entity\TextMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<TextMessage>
 *
 * @method TextMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextMessage[]    findAll()
 * @method TextMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Changes:
 *  - Renamed the repository to TextMessageRepository because I renamed the entity
 *  - Added create() method to handle message creation
 *  - Changed the method by() to findByStatus()
 */
class TextMessageRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextMessage::class);
    }

    /**
     *
     * @param string $text
     * @return int
     */
    public function create(string $text): int
    {
        $textMessage = new TextMessage();
        $textMessage->setUuid(Uuid::v6()->toRfc4122());
        $textMessage->setText($text);
        $textMessage->setStatus(TextMessage::STATUS_SENT);
        $textMessage->setCreatedAt(new \DateTime());

        $this->getEntityManager()->persist($textMessage);
        $this->getEntityManager()->flush();

        return $textMessage->getId();
    }

    /**
     * @param mixed $status
     * @return TextMessage[]
     */
    public function findByStatus($status): array
    {
        if ($status) {
            $result = $this->findBy(['status' => $status]);
        } else {
            $result = $this->findAll();
        }

        return $result;
    }

}
