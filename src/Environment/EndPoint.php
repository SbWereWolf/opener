<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:41
 */

namespace Environment;


interface EndPoint
{
    const USER = '/user/';
    const SESSION = '/session/';
    const LEASE = '/lease/';
    const UNLOCK = '/unlock/';

    const TOKEN = '{token}';
    const POINT = '{point}';
}
