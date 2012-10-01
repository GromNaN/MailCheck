<?php

namespace Tests;

use MailCheck;

class MailCheckTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForTestSuggest
     */
    public function testSuggest($expected, $email)
    {
        $mailcheck = new MailCheck();

        $this->assertEquals($expected, $mailcheck->suggest($email));
    }

    public function dataForTestSuggest()
    {
        return array(
            array('foo@hotmail.com', 'foo@homail.con'),
            array('foo@yahoo.com', 'foo@yaou.com'),
            array('foo@toto.fr', 'foo@toto.fe'),
        );
    }
}