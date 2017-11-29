<?php

namespace Model\Command;

use Model\Entity\Conversation;
use Model\Entity\Message;
use Model\Repository\ConversationRepository;
use Model\Repository\MessageRepository;
use Model\Repository\UserRepository;

class ConversationCommand {

    /**
     * @var ConversationRepository
     */
    private $conversationRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var MessageRepository
     */
    private $messageRepository;

    function __construct(
        ConversationRepository $conversationRepository,
        UserRepository $userRepository,
        MessageRepository $messageRepository
    ) {

        $this->conversationRepository = $conversationRepository;
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
    }

    public function addConversation() {

    }

    public function addMessage( int $conversationId, int $fromUserId, string $text ) {
        $conversation = $this->conversationRepository->find( $conversationId );
        $user = $this->userRepository->find( $fromUserId );

        $message = new Message();
        $message->user = $user;
        $message->conversation = $conversation;
        $message->text = $text;

        $result = $this->messageRepository->persist( $message );

        if( !$result ) {
            throw new \RuntimeException( "Message persist action failed." );
        }
    }

}