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
            ['rgb(138, 7, 7)', 20, 'rgba(110, 5, 5, 1)'],
            ['rgba(138, 7, 7, 0.8)', 20, 'rgba(110, 5, 5, 0.8)'],
            ['#8a0707', 20, 'rgba(110, 5, 5, 1)'],
        ];
    }

    public function testAlpha()
    {
        static::assertSame('rgba(110, 5, 5, 0.5)', static::$extension->alpha('rgba(110, 5, 5, 1)', 0.5));
    }
}
