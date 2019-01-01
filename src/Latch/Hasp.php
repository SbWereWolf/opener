<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:44
 */

namespace Latch;


class Hasp implements IHasp
{
    private $id = 0;
    private $remark = '';
    private $point = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Hasp
     */
    public function setId(int $id): IHasp
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     * @return Hasp
     */
    public function setRemark(string $remark): IHasp
    {
        $this->remark = $remark;
        return $this;
    }

    /**
     * @return string
     */
    public function getPoint(): string
    {
        return $this->point;
    }

    /**
     * @param string $point
     * @return Hasp
     */
    public function setPoint(string $point): IHasp
    {
        $this->point = $point;
        return $this;
    }
}
