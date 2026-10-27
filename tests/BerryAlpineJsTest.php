<?php

declare(strict_types=1);

use function Berry\Html\button;
use function Berry\Html\div;
use function Berry\Html\form;
use function Berry\Html\h1;
use function Berry\Html\input;
use function Berry\Html\span;
use function Berry\Html\template;

test('x-data accepts JavaScript expressions and PHP arrays', function () {
    expect(div()->xData('{ count: 0 }')->toString())
        ->toBe('<div x-data="{ count: 0 }"></div>');

    expect(div()->xData(['count' => 0, 'open' => false])->toString())
        ->toBe('<div x-data="{&quot;count&quot;:0,&quot;open&quot;:false}"></div>');
});

test('basic Alpine directives render attributes', function () {
    expect(h1()->xData(['message' => 'Hello'])->xText('message')->toString())
        ->toBe('<h1 x-data="{&quot;message&quot;:&quot;Hello&quot;}" x-text="message"></h1>');

    expect(div()->xInit('ready = true')->xShow('open')->xEffect('console.log(open)')->toString())
        ->toBe('<div x-init="ready = true" x-show="open" x-effect="console.log(open)"></div>');

    expect(div()->xHtml('content')->xRef('panel')->toString())
        ->toBe('<div x-html="content" x-ref="panel"></div>');
});

test('binding helpers render generic and common x-bind attributes', function () {
    expect(button()->xBind('disabled', 'loading')->toString())
        ->toBe('<button x-bind:disabled="loading"></button>');

    expect(div()->xBindClass('{ hidden: ! open }')->xBindStyle('styles')->toString())
        ->toBe('<div x-bind:class="{ hidden: ! open }" x-bind:style="styles"></div>');

    expect(span()->xBindKey('item.id')->toString())
        ->toBe('<span x-bind:key="item.id"></span>');
});

test('binding helpers encode array and scalar values', function () {
    expect(div()->xBindClass(['hidden' => true, 'block' => false])->toString())
        ->toBe('<div x-bind:class="{&quot;hidden&quot;:true,&quot;block&quot;:false}"></div>');

    expect(button()->xBind('aria-expanded', true)->toString())
        ->toBe('<button x-bind:aria-expanded="true"></button>');

    expect(input()->xBind('tabindex', 2)->toString())
        ->toBe('<input x-bind:tabindex="2" />');
});

test('event helpers render generic and common x-on attributes', function () {
    expect(button()->xOn('mouseenter', 'hovering = true')->toString())
        ->toBe('<button x-on:mouseenter="hovering = true"></button>');

    expect(button()->xOnClick('count++')->toString())
        ->toBe('<button x-on:click="count++"></button>');

    expect(div()->xOnClickOutside('open = false')->toString())
        ->toBe('<div x-on:click.outside="open = false"></div>');

    expect(form()->xOnSubmit('save()')->toString())
        ->toBe('<form x-on:submit="save()"></form>');
});

test('input and keyboard event helpers render Alpine modifiers', function () {
    expect(input()->xOnInput('search = $event.target.value')->toString())
        ->toBe('<input x-on:input="search = $event.target.value" />');

    expect(input()->xOnChange('selected = $event.target.value')->toString())
        ->toBe('<input x-on:change="selected = $event.target.value" />');

    expect(input()->xOnKeydownEnter('submit()')->toString())
        ->toBe('<input x-on:keydown.enter="submit()" />');

    expect(input()->xOnKeydownEscape('open = false')->toString())
        ->toBe('<input x-on:keydown.escape="open = false" />');
});

test('all keyboard event shortcuts render expected events', function () {
    expect(input()->xOnKeydown('pressed = true')->toString())
        ->toBe('<input x-on:keydown="pressed = true" />');

    expect(input()->xOnKeyup('pressed = false')->toString())
        ->toBe('<input x-on:keyup="pressed = false" />');

    expect(input()->xOnKeyupEnter('select()')->toString())
        ->toBe('<input x-on:keyup.enter="select()" />');

    expect(input()->xOnKeyupEscape('cancel()')->toString())
        ->toBe('<input x-on:keyup.escape="cancel()" />');
});

test('generic event helper supports modifiers', function () {
    expect(form()->xOn('submit.prevent', 'save()')->toString())
        ->toBe('<form x-on:submit.prevent="save()"></form>');

    expect(input()->xOn('keyup.debounce.250ms', 'search()')->toString())
        ->toBe('<input x-on:keyup.debounce.250ms="search()" />');
});

test('model, loop and conditional directives render attributes', function () {
    expect(input()->xModel('search')->toString())
        ->toBe('<input x-model="search" />');

    expect(div()->xModelable('value')->toString())
        ->toBe('<div x-modelable="value"></div>');

    expect(
        template()
            ->xFor('item in items')
            ->xBindKey('item.id')
            ->children([span()->xText('item.name')])
            ->toString(),
    )
        ->toBe('<template x-for="item in items" x-bind:key="item.id"><span x-text="item.name"></span></template>');

    expect(template()->xIf('items.length > 0')->children([div()])->toString())
        ->toBe('<template x-if="items.length &gt; 0"><div></div></template>');
});

test('common directives encode boolean numeric and array expressions', function () {
    expect(div()->xShow(false)->toString())
        ->toBe('<div x-show="false"></div>');

    expect(span()->xText(42)->toString())
        ->toBe('<span x-text="42"></span>');

    expect(div()->xEffect(['ready' => true])->toString())
        ->toBe('<div x-effect="{&quot;ready&quot;:true}"></div>');
});

test('flag and suffix directives render correctly', function () {
    expect(div()->xCloak()->toString())
        ->toBe('<div x-cloak></div>');

    expect(div()->xIgnore()->toString())
        ->toBe('<div x-ignore></div>');

    expect(div()->xIgnore('self')->toString())
        ->toBe('<div x-ignore.self></div>');

    expect(div()->xTransition()->toString())
        ->toBe('<div x-transition></div>');

    expect(div()->xTransition('opacity.duration.200ms')->toString())
        ->toBe('<div x-transition.opacity.duration.200ms></div>');

    expect(div()->xTransition('.scale')->toString())
        ->toBe('<div x-transition.scale></div>');

    expect(div()->xTransition(':enter', 'transition ease-out')->toString())
        ->toBe('<div x-transition:enter="transition ease-out"></div>');

    expect(div()->xTransition('enter-start', 'opacity-0')->toString())
        ->toBe('<div x-transition:enter-start="opacity-0"></div>');
});

test('teleport and id directives render attributes', function () {
    expect(template()->xTeleport('body')->children([div()])->toString())
        ->toBe('<template x-teleport="body"><div></div></template>');

    expect(div()->xId(['dropdown-button'])->toString())
        ->toBe('<div x-id="[&quot;dropdown-button&quot;]"></div>');

    expect(div()->xId('dropdown')->toString())
        ->toBe('<div x-id="dropdown"></div>');
});

test('json encoding preserves unicode and slashes for Alpine data', function () {
    expect(div()->xData(['message' => 'I ❤️ Alpine', 'url' => '/docs/start-here'])->toString())
        ->toBe(
            '<div x-data="{&quot;message&quot;:&quot;I ❤️ Alpine&quot;,&quot;url&quot;:&quot;/docs/start-here&quot;}"></div>',
        );
});

test('x-data supports empty component scopes without turning nested arrays into objects', function () {
    expect(div()->xData()->toString())->toBe('<div x-data="{}"></div>');
    expect(div()->xData([])->toString())->toBe('<div x-data="{}"></div>');
    expect(div()->xData(['items' => []])->toString())
        ->toBe('<div x-data="{&quot;items&quot;:[]}"></div>');
});

test('x-bind supports binding objects and attribute modifiers', function () {
    expect(button()->xBind('trigger')->toString())->toBe('<button x-bind="trigger"></button>');
    expect(div()->xBind('{ title: "Hello" }')->toString())
        ->toBe('<div x-bind="{ title: &quot;Hello&quot; }"></div>');
    expect(input()->xBind('value', 'name', 'camel')->toString())
        ->toBe('<input x-bind:value.camel="name" />');
    expect(div()->xBindClass('classes', '.camel')->toString())
        ->toBe('<div x-bind:class.camel="classes"></div>');
});

test('directive modifiers support current Alpine model, show and teleport syntax', function () {
    expect(input()->xModel('amount', 'number.blur')->toString())
        ->toBe('<input x-model.number.blur="amount" />');
    expect(input()->xModel('search', '.debounce.500ms')->toString())
        ->toBe('<input x-model.debounce.500ms="search" />');
    expect(div()->xShow('open', 'important')->toString())
        ->toBe('<div x-show.important="open"></div>');
    expect(template()->xTeleport('#target', 'prepend')->toString())
        ->toBe('<template x-teleport.prepend="#target"></template>');
    expect(template()->xTeleport('#target', 'append')->toString())
        ->toBe('<template x-teleport.append="#target"></template>');
});

test('event shortcuts support modifiers and modifier-only handlers', function () {
    expect(form()->xOnSubmit('save()', 'prevent')->toString())
        ->toBe('<form x-on:submit.prevent="save()"></form>');
    expect(input()->xOnKeydownEnter('save()', 'prevent.once')->toString())
        ->toBe('<input x-on:keydown.enter.prevent.once="save()" />');
    expect(button()->xOnClick(modifier: 'stop')->toString())
        ->toBe('<button x-on:click.stop=""></button>');
    expect(form()->xOn('submit.prevent')->toString())
        ->toBe('<form x-on:submit.prevent=""></form>');
    expect(div()->xOn('custom-event', 'handle()', '.window')->toString())
        ->toBe('<div x-on:custom-event.window="handle()"></div>');
});

test('transition stages use arguments rather than modifiers', function (string $stage) {
    expect(div()->xTransition($stage, 'opacity-0')->toString())
        ->toBe('<div x-transition:' . $stage . '="opacity-0"></div>');
    expect(div()->xTransition(':' . $stage, 'opacity-0')->toString())
        ->toBe('<div x-transition:' . $stage . '="opacity-0"></div>');
})->with(['enter', 'enter-start', 'enter-end', 'leave', 'leave-start', 'leave-end']);

test('transition helper supports per-direction modifiers and empty suffixes', function () {
    expect(div()->xTransition('enter.duration.500ms')->xTransition('leave.duration.400ms')->toString())
        ->toBe('<div x-transition:enter.duration.500ms x-transition:leave.duration.400ms></div>');
    expect(div()->xTransition('')->toString())->toBe('<div x-transition></div>');
    expect(div()->xIgnore('.self')->toString())->toBe('<div x-ignore.self></div>');
    expect(div()->xIgnore('')->toString())->toBe('<div x-ignore></div>');
    expect(div()->xIgnore('.')->toString())->toBe('<div x-ignore></div>');
    expect(div()->xShow('open', '')->toString())->toBe('<div x-show="open"></div>');
});

test('Stringable expressions remain JavaScript expressions', function () {
    $expression = new class implements Stringable {
        public function __toString(): string
        {
            return 'component';
        }
    };

    expect(div()->xData($expression)->toString())->toBe('<div x-data="component"></div>');
});

test('data survives HTML attribute escaping without creating extra attributes or tags', function () {
    $value = '\'"><script>alert("xss")</script>&quot;&';
    $html = div()->xData(['value' => $value])->toString();
    $document = new DOMDocument();
    $document->loadHTML($html);
    $element = $document->getElementsByTagName('div')->item(0);

    expect($element)->toBeInstanceOf(DOMElement::class);
    assert($element instanceof DOMElement);
    expect($element->attributes->length)->toBe(1);
    expect(json_decode($element->getAttribute('x-data'), true, flags: JSON_THROW_ON_ERROR))->toBe(['value' => $value]);
    expect($document->getElementsByTagName('script')->length)->toBe(0);
});

test('invalid JSON data fails explicitly', function () {
    expect(fn() => div()->xData(['value' => "\xB1\x31"]))->toThrow(JsonException::class);
    expect(fn() => input()->xBind('value', [INF]))->toThrow(JsonException::class);
    expect(fn() => input()->xBind('value', INF))->toThrow(JsonException::class);
    expect(fn() => span()->xText(NAN))->toThrow(JsonException::class);
});
