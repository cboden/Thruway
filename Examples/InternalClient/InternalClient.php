<?php
/**
 * This is an example of how to use the InternalClientTransportProvider
 *
 * For more information go to:
 * http://voryx.net/creating-internal-client-thruway/
 */

require "../bootstrap.php";

class InternalClient extends Thruway\Peer\Client {
    function __construct()
    {
        parent::__construct("realm1");


    }

    public function onSessionStart($session, $transport) {
        // TODO: now that the session has started, setup the stuff
        echo "--------------- Hello from InternalClient ------------";
        $this->getCallee()->register($this->session, 'com.example.getphpversion', array($this, 'getPhpVersion'));
    }

    function start()
    {
    }

    function getPhpVersion() {
        return array(phpversion());
    }
}