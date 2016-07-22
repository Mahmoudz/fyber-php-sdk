<?php

namespace mahmoudz\fyberPhpSdk\Contracts;

/**
 * Interface  FyberInterface
 *
 * @author   Mahmoud Zalt  <mahmoud@zalt.me>
 */
interface FyberInterface
{

    /**
     * @param array $data
     * @param       $appType
     *
     * @return  mixed
     */
    public function getOffers(Array $data, $appType);

}
