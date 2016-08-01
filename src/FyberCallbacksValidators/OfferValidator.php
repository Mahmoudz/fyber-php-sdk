<?php

namespace mahmoudz\fyberPhpSdk\FyberCallbacksValidators;

use mahmoudz\fyberPhpSdk\Contracts\Validatable;

/**
 * Class OfferValidator
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class OfferValidator implements Validatable
{

    /**
     * @param $amount
     * @param $userid
     * @param $transid
     * @param $sid
     * @param $token
     *
     * @return  bool
     */
    public function validate($amount, $uid, $transid, $sid, $token)
    {
        // Transaction ID: Only set/available if requested by attaching the _trans_id_ parameter to the callback url.
        // _trans_id_ is always included in the SID calculation, so please ensure to have it as a param of your callback url

        // The statement below assumes you don't have any pubX parameters defined.
        // These are parameters set when redirecting the user to the Fyber offerwall.
        // All pubX (i.e. pub0, pub1, pub2, ...) are passed through unmodified to
        // the callback. Note that they are included in numerical order in the sid
        // computation, e.g.
        // $sha1_of_important_data = sha1($security_token . $userid . $amount . $transid . $_GET['pub0'] .$_GET['pub1'] ....); would be the sid in that case.

        $sha1_of_important_data = sha1($token . $uid . $amount . $transid);

        if ($sid == $sha1_of_important_data) {
            //<CALL WAS GOOD, PAYOUT TO USER, SEND HTTP200 CODE AS ANSWER >
            return true;
        } else {
            //CALL WAS WRONG, DO NOT PAYOUT TO USER, SEND HTTP400 CODE AS ANSWER header ("HTTP/1.0 400 Bad Request: wrong SID");
            return false;
        }
    }
}
