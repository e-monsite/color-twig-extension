<?php

declare(strict_types=1);

namespace Emonsite\ColorTwigExtension\Tests;

use Emonsite\ColorTwigExtension\ColorExtension;
use PHPUnit\Framework\TestCase;

class ColorExtensionTest extends TestCase
{
    /** @var ColorExtension */
    private static $extension;

    public static function setUpBeforeClass(): void
    {
        static::$extension = new ColorExtension();
    }

    /**
     * @dataProvider darkenColorProvider
     */
    public function testDarken(string $color, int $percent, string $expectedResult)
    {
        static::assertSame($expectedResult, static::$extension->darken($color, $percent));
    }

    public function darkenColorProvider()
    {
        return [
            ['rgb(138, 7, 7)', 20, 'rgb(110,6,6)'],
            ['rgba(138, 7, 7, 0.8)', 20, 'rgba(110,6,6,0.8)'],
            ['#8a0707', 20, '#6e0606'],
        ];
    }

    public function testExtremism()
    {
        static::assertSame('#000000', static::$extension->darken('#666666', 100));
        static::assertSame('#ffffff', static::$extension->lighten('#666666', 100));
    }

    public function testAlpha()
    {
        static::assertSame('rgba(110, 5, 5, 0.5)', static::$extension->alpha('rgba(110, 5, 5, 1)', 0.5));
        static::assertSame('rgba(138, 7, 7, 0.5)', static::$extension->alpha('#8a0707', .5));
        static::assertSame('rgba(0, 0, 0, 0.4)', static::$extension->alpha('rgba(0, 0, 0, 0.5)', .8));
    }

    public function testIsDarkAndLight()
    {
        static::assertSame(true, static::$extension->isDark('black'));
        static::assertSame(true, static::$extension->isLight('white'));
        static::assertSame(true, static::$extension->isDark('rgba(140, 140, 140, 1)'));
    }
}
