<?php

namespace mahmoudz\fyberPhpSdk\Contracts;

/**
 * Interface  Validatable
 *
 * @author   Mahmoud Zalt  <mahmoud@zalt.me>
 */
interface Validatable
{

    public function validate($amount, $userid, $transid, $sid, $token);

}
