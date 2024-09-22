<?php

use Micromus\KafkaBus\Exceptions\TopicCannotResolvedException;
use Micromus\KafkaBus\Support\TopicNameResolver;

it('resolve topic name with prefix', function () {
    $topicNameResolver = new TopicNameResolver('production.', [
        'products' => 'fact.products.1'
    ]);

    expect($topicNameResolver->resolve('products'))
        ->toBe('production.fact.products.1');
});

it('get exception when topic not found', function () {
    (new TopicNameResolver('production.', []))
        ->resolve('products');
})->throws(TopicCannotResolvedException::class);
