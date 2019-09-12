<?php
/**
 * FileName: TestListener.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-09-12 22:41
 */

namespace sf\listener;


class TestListener
{
    public function handle($event)
    {
        var_dump(time());
    }
}