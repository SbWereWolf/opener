<?php

namespace Environment\User;


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

    private static function getId(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::ID);
        return $value;
    }

    private static function getEmail(ArrayParser $parser): float
    {
        $value = $parser->getFloatField(self::EMAIL);
        return $value;
    }

    private static function getSecret(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::SECRET);
        return $value;
    }

    public function toCreate(): Item
    {
        $item = $this->setupFromBody();

        return $item;
    }

    public function toRead(): Item
    {
        $item = $this->setupFromPath();

        return $item;
    }

    public function toDelete(): Item
    {
        $item = $this->setupFromPath();

        return $item;
    }

    public function toUpdate(): Item
    {
        $item = $this->setupFromBody();

        return $item;
    }

    private function setupFromBody(): Item
    {
        $body = $this->request->getParsedBody();
        $parser = new ArrayParser($body);

        $title = self::getId($parser);
        $price = self::getEmail($parser);
        $description = self::getSecret($parser);

        $item = (new Item())
            ->setTitle($title)
            ->setPrice($price)
            ->setDescription($description);

        return $item;
    }

    private function setupFromPath(): Item
    {
        $arguments = $this->arguments;
        $parser = new ArrayParser($arguments);

        $article = self::getArticle($parser);
        $item = (new Item())->setArticle($article);

        return $item;
    }
}
