<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 6/19/14
 * Time: 11:43 AM
 */

namespace Thruway\Transport;


use Thruway\Message\Message;
use Thruway\Peer\AbstractPeer;

class InternalClientTransport implements TransportInterface {


    /**
     * @var AbstractPeer
     */
    private $farPeer;

    /**
     * @var TransportInterface
     */
    private $farPeerTransport;

    function __construct(AbstractPeer $farPeer)
    {
        $this->farPeer = $farPeer;
    }

    /**
     * @param \Thruway\Transport\TransportInterface $farPeerTransport
     */
    public function setFarPeerTransport($farPeerTransport)
    {
        $this->farPeerTransport = $farPeerTransport;
    }

    /**
     * @return \Thruway\Transport\TransportInterface
     */
    public function getFarPeerTransport()
    {
        return $this->farPeerTransport;
    }

    public function sendMessage(Message $msg)
    {
        if ($this->getFarPeerTransport() === null) throw new \Exception("You must set the farPeerTransport on internal client transports");

        $this->farPeer->onMessage($this->getFarPeerTransport(), $msg);
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function getTransportDetails()
    {
        return array(
            "type" => "internalClient",
            "transportAddress" => "internal"
        );
    }


} 