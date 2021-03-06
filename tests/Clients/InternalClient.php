<?php

/**
 * This is an example of how to use the InternalClientTransportProvider
 *
 * For more information go to:
 * http://voryx.net/creating-internal-client-thruway/
 */
class InternalClient extends Thruway\Peer\Client
{
    /**
     * @var \Thruway\Peer\Router
     */
    private $router;

    public function onSessionStart($session, $transport)
    {
        $this->getCallee()->register($this->session, 'com.example.testcall', array($this, 'callTheTestCall'));

        $this->getCallee()->register($this->session, 'com.example.publish', array($this, 'callPublish'));

        $this->getCallee()->register(
            $this->session,
            'com.example.ping',
            array($this, 'callPing'),
            ['discloseCaller' => true]
        );

    }

    public function start()
    {
    }

    public function callTheTestCall($res)
    {
        return array($res[0]);
    }

    public function callPublish($args)
    {
        $deferred = new \React\Promise\Deferred();

        $this->getPublisher()->publish($this->session, "com.example.publish", [$args[0]], ["key1" => "test1", "key2" => "test2"], ["acknowledge" => true])
            ->then(
                function () use ($deferred) {
                    $deferred->resolve('ok');
                },
                function ($error) use ($deferred) {
                    $deferred->reject("failed: {$error}");
                }
            );

        return $deferred->promise();
    }

    public function callPing($args, $kwArgs, $details) {
        if ($this->router === null) throw new \Exception("Router must be set before calling ping.");

        if (isset($details['caller'])) {
            $sessionIdToPing = $details['caller'];

            $theSession = $this->getRouter()->getSessionBySessionId($sessionIdToPing);
            return $theSession->ping(10);
        }

        return array("no good");
    }

    /**
     * @param \Thruway\Peer\Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return \Thruway\Peer\Router
     */
    public function getRouter()
    {
        return $this->router;
    }



}