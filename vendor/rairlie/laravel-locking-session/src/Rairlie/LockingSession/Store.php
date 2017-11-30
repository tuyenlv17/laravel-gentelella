<?php
namespace Rairlie\LockingSession;

use Illuminate\Session\Store as BaseStore;

class Store extends BaseStore
{

    public function __construct($name, $realHandler, $id = null, $lockfileDir = null)
    {
        $lockingSessionHandler = new LockingSessionHandler($realHandler, $lockfileDir);

        return parent::__construct($name, $lockingSessionHandler, $id);
    }

    public function handlerNeedsRequest()
    {
        return $this->handler->needsRequest();
    }

}
