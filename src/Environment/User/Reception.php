<?php

namespace Environment\User;


use BusinessLogic\Person\IPerson;
use BusinessLogic\Person\Person;
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
     * @return IPerson
     */
    public function toCreate(): IPerson
    {
        $item = $this->setupFromBody();

        return $item;
    }

    private function setupFromBody(): IPerson
    {
        $body = $this->getRequest()->getParsedBody();
        $this->setParser(new ArrayParser($body));

        $user = $this->setupUser();

        return $user;
    }

    private function setupUser(): IPerson
    {
        $email = $this->getEmail();
        $password = $this->getPassword();

        $user = (new Person())
            ->setEmail($email)
            ->setPassword($password);

        return $user;
    }
}
