<?php

namespace AppBundle\Topic;

use AppBundle\Service\FileLogger;
use JDare\ClankBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface as Conn;

class AppTopic implements TopicInterface {
    /** @var FileLogger $logger */
    private $logger;

    /**
     * @param FileLogger $logger
     */
    public function __construct($logger) {
        $this->logger = $logger;
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return void
     */
    public function onSubscribe(Conn $conn, $topic) {
        $topic->broadcast($conn->resourceId . " has joined " . $topic->getId());
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return void
     */
    public function onUnSubscribe(Conn $conn, $topic) {
        $topic->broadcast($conn->resourceId . " has left " . $topic->getId());
    }

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $this->logger->logEvent($topic->getId(), $event['msg']);
        $topic->broadcast(array(
            "sender" => $conn->resourceId,
            "topic" => $topic->getId(),
            "event" => $event
        ));
    }

    public function getName() {
        return 'app.topic';
    }

}