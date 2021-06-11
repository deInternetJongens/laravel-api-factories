<?php

declare(strict_types=1);

namespace DIJ\ApiFactories\Tests;

use DIJ\ApiFactories\ApiFactory;

class ApiFactoryTest extends TestCase
{
    public function testExpandedClosureAttributesAreResolvedAndPassedToClosures(): void
    {
        $user = FactoryTestUserFactory::new()->make(
            [
                'name' => function () {
                    return 'taylor';
                },
                'options' => function ($attributes) {
                    return $attributes['name'] . '-options';
                },
            ]
        );

        $this->assertSame('taylor-options', $user['options']);
    }

    public function testMakeCreatesArray(): void
    {
        $user = FactoryTestUserFactory::new()->makeOne();
        $this->assertIsArray($user);

        $user = FactoryTestUserFactory::new()->make(['name' => 'Taylor Otwell']);

        $this->assertSame('Taylor Otwell', $user['name']);
    }

    public function testJsonCreatesAJsonString(): void
    {
        $user = FactoryTestUserFactory::new()->makeOne();
        $this->assertIsArray($user);

        $user = FactoryTestUserFactory::new()->json(['name' => 'Taylor Otwell']);

        $this->assertJson($user);
        $this->assertSame('Taylor Otwell', json_decode($user)->name);
    }

    public function testBasicAttributesCanBeCreated(): void
    {
        $user = FactoryTestUserFactory::new()->raw();
        $this->assertIsArray($user);

        $user = FactoryTestUserFactory::new()->raw(['name' => 'Taylor Otwell']);
        $this->assertIsArray($user);
        $this->assertSame('Taylor Otwell', $user['name']);
    }

    public function testExpandedAttributesCanBeMade(): void
    {
        $post = FactoryTestPostFactory::new()->raw();
        $this->assertIsArray($post);

        $post = FactoryTestPostFactory::new()->raw(['title' => 'Test Title']);
        $this->assertIsArray($post);
        $this->assertIsArray($post['user']);
        $this->assertIsString($post['user']['name']);
        $this->assertSame('Test Title', $post['title']);
    }

    public function testMultipleModelAttributesCanBeCreated(): void
    {
        $posts = FactoryTestPostFactory::new()->times(10)->raw();
        $this->assertIsArray($posts);

        $this->assertCount(10, $posts);
    }

    public function testAfterCreatingAndMakingCallbacksAreCalled(): void
    {
        $user = FactoryTestUserFactory::new()
            ->afterMaking(
                function ($user) {
                    $_SERVER['__test.user.making'] = $user;
                }
            )
            ->make();

        $this->assertSame($user, $_SERVER['__test.user.making']);

        unset($_SERVER['__test.user.making']);
    }

    public function testSequences(): void
    {
        $users = FactoryTestUserFactory::times(2)->sequence(
            ['name' => 'Taylor Otwell'],
            ['name' => 'Abigail Otwell'],
        )->make();

        $this->assertSame('Taylor Otwell', $users[0]['name']);
        $this->assertSame('Abigail Otwell', $users[1]['name']);
    }

    public function testCanBeMacroable(): void
    {
        $factory = FactoryTestUserFactory::new();
        $factory->macro(
            'getFoo',
            function () {
                return 'Hello World';
            }
        );

        $this->assertSame('Hello World', $factory->getFoo());
    }
}

class FactoryTestUserFactory extends ApiFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'options' => null,
        ];
    }
}

class FactoryTestPostFactory extends ApiFactory
{
    public function definition(): array
    {
        return [
            'user' => FactoryTestUserFactory::new(),
            'title' => $this->faker->name,
        ];
    }
}
