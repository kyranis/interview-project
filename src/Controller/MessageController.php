<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\SendMessage;
use App\Repository\TextMessageRepository;
use Controller\MessageControllerTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @see MessageControllerTest
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
 */
class MessageController extends AbstractController
{
    /**
     * @var TextMessageRepository
     */
    private TextMessageRepository $textMessageRepo;

    /**
     * Change: Instantiation the Repository as dependency of the class instead of instantiation in the method
     * @param TextMessageRepository $TextMessageRepository
     */
    public function __construct(TextMessageRepository $TextMessageRepository)
    {
        $this->textMessageRepo = $TextMessageRepository;
    }
    /**
     * Changes:
     *  - renaming the method By() to findByStatus() for better readability and understanding
     *  - Instead of using the request object as an argument, I used only the status value for the method findByStatus
     *  - Request object should not be handled in the Repository layer
     *  - Instead of the foreach loop I used array_map
     *  - Better response handling using JsonResponse
     */
    #[Route('/messages', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $status = $request->get('status');
        $textMessages = $this->textMessageRepo->findByStatus($status);
        $result = [];

        if(!empty($textMessages)) {
            $result = array_map(fn ($message) => [
                'uuid' => $message->getUuid(),
                'text' => $message->getText(),
                'status' => $message->getStatus()
            ], $textMessages);
        }

        return $this->json(['messages',$result], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param MessageBusInterface $bus
     * @return Response
     */
    /**
     * Changes:
     *  - Use POST method instead of GET more appropriate for sending messages
     *  - Use the proper HTTP status in the Response
     */

    #[Route('/messages/send', methods: ['POST'])]
    public function send(Request $request, MessageBusInterface $bus): Response
    {
        $text = $request->get('text');
        
        if (!$text || !is_string($text)) {
            return $this->json(['error' => 'Text is required'], Response::HTTP_BAD_REQUEST);
        }

        $bus->dispatch(new SendMessage($text));

        return $this->json(['success' => 'Successfully sent'], Response::HTTP_CREATED);
    }
}