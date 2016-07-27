<?php

namespace mahmoudz\fyberPhpSdk;

use stdClass;

/**
 * Class Offers
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Offers
{

    /**
     * @var  \stdClass
     */
    private $content;

    /**
     * Offers constructor.
     *
     * @param \stdClass $content
     */
    public function __construct(stdClass $content)
    {
        $this->content = $content;
    }

    /**
     * @return  array
     */
    public function getAll()
    {
        return $this->content->offers;
    }

    /**
     * @return  mixed
     */
    public function getAppId()
    {
        return $this->content->information->appid;
    }
}
