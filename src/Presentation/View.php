<?php
/**
 * Project: opener
 * Author: SbWEreWolf
 * DateTime: 02.01.2019 2:21
 */

namespace Presentation;


interface View
{

    public function toJson(): string;

    public function toArray(): array;
}
