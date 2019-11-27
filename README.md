# Color Twig Extension

## install
```bash
composer require e-monsite/color-twig-extension
```

Then add the bundle to yours bundles if you are on Symfony:

```php 
// config/bundles.php
return [
    Emonsite\ColorTwigExtension\ColorExtensionBundle::class => ['all' => true],
];
```

## Usage

There is some examples:
```twig
{{ 'rgb(138, 7, 7)' | darken(20) }}
{{ '#8a0707' | lighten(40) }}
{{ 'green' | alpha(0.5) }}
{% if myColor is dark %} ... {% endif %}
{% if myColor2 is light %} ... {% endif %}
```
