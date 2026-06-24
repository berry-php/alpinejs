<?php declare(strict_types=1);

namespace Berry\AlpineJs;

use Berry\Html\HtmlTag;
use Berry\Html\HtmlVoidTag;
use Closure;
use JsonException;
use Stringable;

final readonly class BerryAlpineJs
{
    public static function install(): void
    {
        $methods = [
            'xData' => static::xData(),
            'xInit' => static::directive('x-init'),
            'xShow' => static::directive('x-show'),
            'xBind' => static::xBind(),
            'xBindClass' => static::xBindShortcut('class'),
            'xBindStyle' => static::xBindShortcut('style'),
            'xBindKey' => static::xBindShortcut('key'),
            'xOn' => static::xOn(),
            'xOnClick' => static::xOnShortcut('click'),
            'xOnClickOutside' => static::xOnShortcut('click.outside'),
            'xOnSubmit' => static::xOnShortcut('submit'),
            'xOnInput' => static::xOnShortcut('input'),
            'xOnChange' => static::xOnShortcut('change'),
            'xOnKeydown' => static::xOnShortcut('keydown'),
            'xOnKeydownEnter' => static::xOnShortcut('keydown.enter'),
            'xOnKeydownEscape' => static::xOnShortcut('keydown.escape'),
            'xOnKeyup' => static::xOnShortcut('keyup'),
            'xOnKeyupEnter' => static::xOnShortcut('keyup.enter'),
            'xOnKeyupEscape' => static::xOnShortcut('keyup.escape'),
            'xText' => static::directive('x-text'),
            'xHtml' => static::directive('x-html'),
            'xModel' => static::directive('x-model'),
            'xModelable' => static::directive('x-modelable'),
            'xFor' => static::directive('x-for'),
            'xTransition' => static::xTransition(),
            'xEffect' => static::directive('x-effect'),
            'xIgnore' => static::xIgnore(),
            'xRef' => static::directive('x-ref'),
            'xCloak' => static::flag('x-cloak'),
            'xTeleport' => static::directive('x-teleport'),
            'xIf' => static::directive('x-if'),
            'xId' => static::xId(),
        ];

        foreach ($methods as $name => $func) {
            HtmlTag::addMethod($name, $func);
            HtmlVoidTag::addMethod($name, $func);
        }
    }

    private static function xData(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, array|string|Stringable $data): HtmlTag|HtmlVoidTag {
            return $node->attr('x-data', static::value($data));
        };
    }

    private static function xBind(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, string $attribute, array|bool|float|int|string|Stringable $expression): HtmlTag|HtmlVoidTag {
            return $node->attr("x-bind:$attribute", static::value($expression));
        };
    }

    private static function xBindShortcut(string $attribute): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, array|bool|float|int|string|Stringable $expression) use ($attribute): HtmlTag|HtmlVoidTag {
            return $node->attr("x-bind:$attribute", static::value($expression));
        };
    }

    private static function xOn(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, string $event, string|Stringable $expression): HtmlTag|HtmlVoidTag {
            return $node->attr("x-on:$event", static::value($expression));
        };
    }

    private static function xOnShortcut(string $event): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, string|Stringable $expression) use ($event): HtmlTag|HtmlVoidTag {
            return $node->attr("x-on:$event", static::value($expression));
        };
    }

    private static function xTransition(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, string|null $suffix = null, string|Stringable|null $expression = null): HtmlTag|HtmlVoidTag {
            $attribute = 'x-transition';

            if ($suffix !== null) {
                $attribute .= str_starts_with($suffix, ':') || str_starts_with($suffix, '.') ? $suffix : ".$suffix";
            }

            if ($expression === null) {
                return $node->flag($attribute);
            }

            return $node->attr($attribute, static::value($expression));
        };
    }

    private static function xIgnore(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, string|null $modifier = null): HtmlTag|HtmlVoidTag {
            return $node->flag($modifier === null ? 'x-ignore' : "x-ignore.$modifier");
        };
    }

    private static function xId(): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, array|string|Stringable $names): HtmlTag|HtmlVoidTag {
            return $node->attr('x-id', static::value($names));
        };
    }

    private static function directive(string $attribute): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node, array|bool|float|int|string|Stringable $expression) use ($attribute): HtmlTag|HtmlVoidTag {
            return $node->attr($attribute, static::value($expression));
        };
    }

    private static function flag(string $attribute): Closure
    {
        return function (HtmlTag|HtmlVoidTag $node) use ($attribute): HtmlTag|HtmlVoidTag {
            return $node->flag($attribute);
        };
    }

    /**
     * @param array<mixed>|bool|float|int|string|Stringable $value
     *
     * @throws JsonException
     */
    private static function value(array|bool|float|int|string|Stringable $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return strval($value);
    }
}
