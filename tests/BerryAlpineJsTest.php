<?php declare(strict_types=1);

use Berry\AlpineJs\BerryAlpineJs;

use function Berry\Html\button;
use function Berry\Html\div;
use function Berry\Html\form;
use function Berry\Html\h1;
use function Berry\Html\input;
use function Berry\Html\span;

beforeAll(function () {
    BerryAlpineJs::install();
});

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

    expect(div()->xModelable('value')->xFor('item in items')->xIf('items.length > 0')->toString())
        ->toBe('<div x-modelable="value" x-for="item in items" x-if="items.length &gt; 0"></div>');
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
        ->toBe('<div x-transition.enter-start="opacity-0"></div>');
});

test('teleport and id directives render attributes', function () {
    expect(div()->xTeleport('body')->toString())
        ->toBe('<div x-teleport="body"></div>');

    expect(div()->xId(['dropdown-button'])->toString())
        ->toBe('<div x-id="[&quot;dropdown-button&quot;]"></div>');

    expect(div()->xId('dropdown')->toString())
        ->toBe('<div x-id="dropdown"></div>');
});

test('json encoding preserves unicode and slashes for Alpine data', function () {
    expect(div()->xData(['message' => 'I ❤️ Alpine', 'url' => '/docs/start-here'])->toString())
        ->toBe('<div x-data="{&quot;message&quot;:&quot;I ❤️ Alpine&quot;,&quot;url&quot;:&quot;/docs/start-here&quot;}"></div>');
});
