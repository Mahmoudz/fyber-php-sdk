<?php
namespace mahmoudz\fyberPhpSdk\Contracts;

/**
 * Interface  FyberInterface
 * @package  mahmoudz\fyberPhpSdk\Contracts
 * @author   Mahmoud Zalt  <mahmoud@zalt.me>
 */
interface FyberInterface
{

    /**
     * @param $data
     *
     * @return  mixed
     */
    public function getOffers(Array $data);

}
