<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:44
 */

namespace Latch;


interface IHasp
{
    public function setId(int $id): IHasp;

    public function getId(): int;

    public function setRemark(string $remark): IHasp;

    public function getRemark(): string;

    public function setPoint(string $point): IHasp;

    public function getPoint(): string;
}
