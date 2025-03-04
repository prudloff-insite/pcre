<?php

/*
 * This file is part of composer/pcre.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Pcre\RegexTests;

use Composer\Pcre\BaseTestCase;
use Composer\Pcre\Regex;

class MatchTest extends BaseTestCase
{
    /**
     * This can be replaced with a setUp() method when appropriate
     *
     * @before
     * @return void
     */
    public function registerFunctionName()
    {
        $this->pregFunction = 'preg_match()';
    }

    /**
     * @return void
     */
    public function testSuccess()
    {
        $result = Regex::match('{(?P<m>[io])}', 'abcdefghijklmnopqrstuvwxyz');
        self::assertInstanceOf('Composer\Pcre\MatchResult', $result);
        self::assertTrue($result->matched);
        self::assertSame(array(0 => 'i', 'm' => 'i', 1 => 'i'), $result->matches);
    }

    /**
     * @return void
     */
    public function testFailure()
    {
        $result = Regex::match('{abc}', 'def');
        self::assertInstanceOf('Composer\Pcre\MatchResult', $result);
        self::assertFalse($result->matched);
        self::assertSame(array(), $result->matches);
    }

    /**
     * @return void
     */
    public function testBadPatternThrowsIfWarningsAreNotThrowing()
    {
        $this->expectPcreException($pattern = '{abc');
        @Regex::match($pattern, 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testBadPatternTriggersWarningByDefault()
    {
        $this->expectPcreWarning();
        Regex::match('{abc', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * @return void
     */
    public function testThrowsIfEngineErrors()
    {
        $this->expectPcreEngineException($pattern = '/(?:\D+|<\d+>)*[!?]/');
        Regex::match($pattern, 'foobar foobar foobar');
    }
}
