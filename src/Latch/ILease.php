<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:04
 */

namespace Latch;

/**
 * @SWG\Definition(
 *   definition="lease",
 *   type="object",
 *   description="lease of latch",
 *   @SWG\Property(
 *       property="user-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="shutter-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="start",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="finish",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="occupancy-type-id",
 *       type="integer",
 *   ),
 * )
 * @SWG\Definition(
 *   definition="lease-with-token",
 *   type="object",
 *   description="lease of latch",
 *   @SWG\Property(
 *       property="token",
 *       type="string",
 *   ),
 *   @SWG\Property(
 *       property="user-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="shutter-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="start",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="finish",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="occupancy-type-id",
 *       type="integer",
 *   ),
 * )
 * @SWG\Definition(
 *   definition="lease-with-id",
 *   type="object",
 *   description="lease of latch",
 *   @SWG\Property(
 *       property="id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="user-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="shutter-id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="start",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="finish",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="occupancy-type-id",
 *       type="integer",
 *   ),
 * )
 */
interface ILease extends Token
{

    public function setId(int $id): self;

    public function setToken(string $token): self;

    /**
     * @return int
     */
    public function getId(): int;

    public function setShutterId(int $shutterId): self;

    /**
     * @return int
     */
    public function getShutterId(): int;

    public function setStart(int $start): self;

    /**
     * @return int
     */
    public function getStart(): int;

    public function setFinish(int $finish): self;

    /**
     * @return int
     */
    public function getFinish(): int;

    public function setOccupancyTypeId(int $occupancyTypeId): self;

    /**
     * @return int
     */
    public function getOccupancyTypeId(): int;

    public function setUserId(int $userId): ILease;

    /**
     * @return int
     */
    public function getUserId(): int;
}
