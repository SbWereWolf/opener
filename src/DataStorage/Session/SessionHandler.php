<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\Session;


use BusinessLogic\Basis\Content;
use BusinessLogic\Session\ISession;
use BusinessLogic\Session\SessionSet;
use DataStorage\Basis\DataHandler;

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
                foreach ($sessionAccess->getData()->next() as $dataElement){
                    $storedSession = $dataElement;
                    break;
                }


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

        $isSuccess = $result->isSuccess();
        if($isSuccess){
            session_start();
            session_destroy();
        }


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
