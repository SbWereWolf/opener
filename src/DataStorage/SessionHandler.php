<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage;


use Latch\Content;
use Latch\ISession;
use Latch\SessionSet;

class SessionHandler extends DataHandler
{
    private $sessionAccess = null;

    public function prolong(ISession $session): Content
    {
        $result = new SessionSet();

        $this->begin();
        try {
            $sessionAccess = $this->getSessionAccess()->select($session);

            $isValid = $sessionAccess->getRowCount() > 0;
            if ($isValid) {
                /** @var ISession $storedSession */
                $storedSession = $sessionAccess->getData()->next();

                $isValid = $storedSession->getFinish() > 0;
            }

            if ($isValid) {
                $result = $this->getSessionAccess()->insertWithToken($session)->getData();
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }

    public function finish(ISession $session): Content
    {
        $result = $this->getSessionAccess()->delete($session)->getData();

        return $result;
    }

    /**
     * @return SessionAccess
     */
    private function getSessionAccess(): SessionAccess
    {
        $sessionAccess = $this->sessionAccess;
        $isExists = !empty($sessionAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $sessionAccess = new SessionAccess($access);
            $this->sessionAccess = $sessionAccess;
        }

        return $sessionAccess;
    }
}
