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
     * @return  array of std objects
     */
    public function getAll()
    {
        if (!isset($this->content->offers)) {
            return [];
        }

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
