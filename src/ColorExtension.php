<?php

declare(strict_types=1);

namespace Emonsite\ColorTwigExtension;

use MikeAlmond\Color\Color;
use MikeAlmond\Color\CssGenerator;
use MikeAlmond\Color\Exceptions\InvalidColorException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * Some filter to manipulate colors in twig
 */
class ColorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('darken', [$this, 'darken']),
            new TwigFilter('lighten', [$this, 'lighten']),
            new TwigFilter('alpha', [$this, 'alpha']),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('dark', [$this, 'isDark']),
            new TwigTest('light', [$this, 'isLight']),
        ];
    }

    public function darken(string $colorAsString, int $percent): string
    {
        return $this->shade($colorAsString, -round($percent / 100, 2),);
    }

    public function lighten(string $colorAsString, int $percent): string
    {
        return $this->shade($colorAsString, round($percent / 100, 2),);
    }

    /**
     * Set the alpha (transparence) of a color
     * result alpha will be relative to source color alpha
     */
    public function alpha(string $colorAsString, float $newAlpha): string
    {
        try {
            /** @var Color $color */
            [$color, $alpha] = $this->parseColor($colorAsString);
        } catch (InvalidColorException $e) {
            return $colorAsString;
        }

        return CssGenerator::rgba($color, round($alpha*$newAlpha, 2));
    }

    public function isDark(string $colorAsString): bool
    {
        /** @var Color $color */
        $color = $this->parseColor($colorAsString)[0];

        return $color->getLuminosity() < 0.5;
    }

    public function isLight(string $colorAsString): bool
    {
        return !$this->isDark($colorAsString);
    }

    /**
     * Shade a color to white or black.
     * @param float $ratio -1 to 1
     */
    private function shade(string $color, float $ratio): string
    {
        $newColor = \SBC::Shade($ratio, $color);

        if ($newColor === null) {
            throw new InvalidColorException("$color is not a color");
        }

        return $newColor;
    }

    /**
     * @return array [Color $color, mixed $alpha]
     * @throws InvalidColorException if unable to parse to a proper color
     */
    private function parseColor(string $colorAsString): array
    {
        $colorAsString = trim(mb_strtolower($colorAsString));

        // parse hexa
        if (mb_strpos($colorAsString, '#') === 0) {
            return [Color::fromHex($colorAsString), '1'];
        }

        // parse rgb(a)
        if (mb_strpos($colorAsString, 'rgb') === 0) {
            $colorAsString = str_replace(['rgba', 'rgb'], '', $colorAsString);
            $colorAsString = trim($colorAsString, '()');
            $parts = explode(',', $colorAsString);

            switch (count($parts)) {
                case 3:
                    [$r, $g, $b] = $parts;
                    break;
                case 4:
                    [$r, $g, $b, $alpha] = $parts;
                    break;
                default:
                    throw new InvalidColorException('error parsing rgba color');
            }


            return [Color::fromRgb((int) $r, (int) $g, (int) $b), $alpha ?? 1];
        }

        // fallback in "name" (ex : green)
        return [Color::fromCssColor($colorAsString), 1];
    }
}
