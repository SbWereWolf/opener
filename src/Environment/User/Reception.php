<?php

namespace Environment\User;


use BusinessLogic\User\IUser;
use BusinessLogic\User\User;
use LanguageFeatures\ArrayParser;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Basis\Reception
{
    const EMAIL = 'email';
    const PASSWORD = 'password';

    private function getEmail(): string
    {
        $value = $this->getParser()->getStringField(self::EMAIL);
        return $value;
    }

    private function getPassword(): string
    {
        $value = $this->getParser()->getStringField(self::PASSWORD);
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

        $user = $this->setupUser();

        return $user;
    }

    private function setupUser(): IUser
    {
        $email = $this->getEmail();
        $password = $this->getPassword();

        $user = (new User())
            ->setEmail($email)
            ->setPassword($password);

        return $user;
    }
}
