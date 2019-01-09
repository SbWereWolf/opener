<?php

namespace Environment\User;


use LanguageFeatures\ArrayParser;
use Latch\IUser;
use Latch\User;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Reception
{
    const ID = 'id';
    const EMAIL = 'email';
    const SECRET = 'secret';

    private function getId(): int
    {
        $value = $this->getParser()->getIntegerField(self::ID);
        return $value;
    }

    private function getEmail(): int
    {
        $value = $this->getParser()->getIntegerField(self::EMAIL);
        return $value;
    }

    private function getSecret(): int
    {
        $value = $this->getParser()->getIntegerField(self::SECRET);
        return $value;
    }

    /**
     * @return IUser
     */
    public function toCreate(): IUser
    {
        $item = $this->setupFromBody();

        return $item;
    }

    private function setupFromBody(): IUser
    {
        $body = $this->getRequest()->getParsedBody();
        $this->setParser(new ArrayParser($body));

        $user = $this->setUpUser();

        return $user;
    }

    private function setUpUser(): IUser
    {
        $id = $this->getId();
        $email = $this->getEmail();
        $secret = $this->getSecret();

        $user = (new User())
            ->setId($id)
            ->setEmail($email)
            ->setSecret($secret);

        return $user;
    }
}
