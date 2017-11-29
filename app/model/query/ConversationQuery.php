<?php

namespace Model\Query;

use Model\Repository\ConversationMemberRepository;
use Model\Repository\ConversationRepository;
use Model\Repository\UserRepository;
use Nette\Security\User;

class ConversationQuery {

    /**
     * @var ConversationMemberRepository
     */
    private $conversationMemberRepository;
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    function __construct(
        ConversationMemberRepository $conversationMemberRepository,
        ConversationRepository $conversationRepository,
        UserRepository $userRepository
    ) {

        $this->conversationMemberRepository = $conversationMemberRepository;
        $this->conversationRepository = $conversationRepository;
        $this->userRepository = $userRepository;
    }

    public function getUserConversations( int $userId ) {
        $user = $this->userRepository->find( $userId );
        $conversations = $this->conversationMemberRepository->findByUser( $user->id );

        return $conversations;
    }

}