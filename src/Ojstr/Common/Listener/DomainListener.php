<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\EventDispatcher\Event;

class DomainListener {

    public function onDomainParse(Event $event) {
        $request = $event->getRequest();
        $host = $request->getHost();
    }

}
