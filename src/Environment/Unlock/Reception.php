<?php

namespace Environment\Unlock;

use Slim\Http\Request;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Reception
{
    const ARTICLE = 'article';
    const TITLE = 'title';
    const PRICE = 'price';
    const DESCRIPTION = 'description';
    const WEIGHT = 'weight';

    private $request;
    private $arguments;

    function __construct(Request $request, array $arguments)
    {
        $this->request = $request;
        $this->arguments = $arguments;
    }

    private static function getTitle(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::TITLE);
        return $value;
    }

    private static function getPrice(ArrayParser $parser): float
    {
        $value = $parser->getFloatField(self::PRICE);
        return $value;
    }

    private static function getDescription(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::DESCRIPTION);
        return $value;
    }

    private static function getWeight(ArrayParser $parser): float
    {
        $value = $parser->getFloatField(self::WEIGHT);
        return $value;
    }

    private static function getArticle(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::ARTICLE);
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

        $title = self::getTitle($parser);
        $price = self::getPrice($parser);
        $description = self::getDescription($parser);
        $weight = self::getWeight($parser);
        $article = self::getArticle($parser);

        $item = (new Item())
            ->setTitle($title)
            ->setPrice($price)
            ->setDescription($description)
            ->setWeight($weight)
            ->setArticle($article);

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
