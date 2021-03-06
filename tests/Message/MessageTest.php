<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 8/23/14
 * Time: 12:21 AM
 */

namespace Message;


use Thruway\Message\Message;
use Thruway\Message\PingMessage;
use Thruway\Message\PongMessage;

class MessageTest extends \PHPUnit_Framework_TestCase {
    function testPingMessage() {
        $rawPing = "[260, 12345]";
        $pingMsg = Message::createMessageFromRaw($rawPing);

        $this->assertTrue($pingMsg instanceof PingMessage, "Serialized to PingMessage class");
        $this->assertTrue($pingMsg->getRequestId() == 12345, "Request id preserved");

        $this->assertEquals(json_encode(json_decode($rawPing)),
            $pingMsg->getSerializedMessage(),
            "Serialized version matches"
        );

        $rawPing = "[260, 12345, {\"test\": \"good\"}, [67890], \"discard string\"]";
        $pingMsg = Message::createMessageFromRaw($rawPing);

        $this->assertTrue($pingMsg instanceof PingMessage, "Serialized to PingMessage class");
        /** @var $pingMsg PingMessage */
        $this->assertTrue($pingMsg->getRequestId() == 12345, "Request id preserved");
        $this->assertTrue($pingMsg->getOptions()['test'] == "good", "Details deserialized correctly");
        $this->assertTrue($pingMsg->getEcho()[0] == "67890", "Echo deserialized correctly");
        $this->assertTrue($pingMsg->getDiscard() == "discard string", "Echo deserialized correctly");

        $pongMsg = $pingMsg->getPong();

        $this->assertEquals(12345, $pongMsg->getRequestId(), "Pong created with correct request id");
        $this->assertEquals(67890, $pongMsg->getEcho()[0], "Echo in the pong is correct");

        $this->assertEquals(json_encode(json_decode($rawPing)),
            $pingMsg->getSerializedMessage(),
            "Serialized version matches"
        );
    }

    function testPongMessage() {
        $rawPong = "[261, 12345]";
        $pongMsg = Message::createMessageFromRaw($rawPong);

        $this->assertTrue($pongMsg instanceof PongMessage, "Serialized to PongMessage class");
        $this->assertTrue($pongMsg->getRequestId() == 12345, "Request id preserved");

        $this->assertEquals(json_encode(json_decode($rawPong)),
            $pongMsg->getSerializedMessage(),
            "Serialized version matches"
        );

        $rawPong = "[261, 12345, {\"test\": \"good\"}, [67890]]";
        $pongMsg = Message::createMessageFromRaw($rawPong);

        $this->assertTrue($pongMsg instanceof PongMessage, "Serialized to PongMessage class");
        /** @var $pongMsg PongMessage */
        $this->assertTrue($pongMsg->getRequestId() == 12345, "Request id preserved");
        $this->assertTrue($pongMsg->getDetails()['test'] == "good", "Details deserialized correctly");
        $this->assertTrue($pongMsg->getEcho()[0] == "67890", "Echo deserialized correctly");

        $this->assertEquals(json_encode(json_decode($rawPong)),
            $pongMsg->getSerializedMessage(),
            "Serialized version matches"
        );
    }
} 