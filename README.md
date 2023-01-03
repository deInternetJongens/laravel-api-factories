<p align="center"><img src="https://banners.beyondco.de/Api%20Factories.png?theme=light&packageManager=composer+require&packageName=deinternetjongens%2Flaravel-api-factories&pattern=architect&style=style_1&description=Get+the+database+factories+experience+with+fake+Http+call+in+your+testsuite&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" alt="Social Card of Laravel Api Factories"></p>

# Laravel Api Factories

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deinternetjongens/laravel-api-factories.svg?style=flat-square)](https://packagist.org/packages/deinternetjongens/laravel-api-factories)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/deinternetjongens/laravel-api-factories/run-tests?label=tests)](https://github.com/deinternetjongens/laravel-api-factories/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/deinternetjongens/laravel-api-factories/Check%20&%20fix%20styling?label=code%20style)](https://github.com/deinternetjongens/laravel-api-factories/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/deinternetjongens/laravel-api-factories.svg?style=flat-square)](https://packagist.org/packages/deinternetjongens/laravel-api-factories)

This package provides the database factory experience to fake Http calls in your testsuite

## Installation

You can install the package via composer:

```bash
composer require deinternetjongens/laravel-api-factories
```

## Usage

### Generate Api factories

```bash
php artisan make:api-factory NewsPostResponse
```

The new api factory class will be placed in your tests/Factories directory.

### Configure api factory

The api factories look moslty the same as the Laravel database factories, except it extend the `ApiFactory` class and
you don't need to specify a model.

```php
namespace Tests\Factories;

use DIJ\ApiFactories\ApiFactory;

class NewsPostResponseFactory extends ApiFactory
{
    protected ?string $wrapper = ResponseFactoryWrapper::class;
    
    /**
     * Define the response's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'intro' => $this->faker->paragraph(),
            'article' => $this->faker->paragraphs(4),
            'author' => $this->faker->name,
            'likes' => $this->faker->randomNumber(2),
            'published_at' => $this->faker->dateTime(),
        ];
    }
}

class ResponseFactoryWrapper extends ApiFactory
{
    protected ?string $wrapper = 'data';
    
    /**
     * Define the response's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'items' => $this->children(),
            'meta' => [
                'total' => rand(0, 10),
            ]
        ];
    }
}
```

### Use api factory

```php
use \Illuminate\Support\Facades\Http;

$response = NewsPostResponseFactory::new()
    ->state(new Sequence(
        ['author' => 'Taylor'],
        ['author' => 'Mohammed'],
        ['author' => 'Dries']
    ))
    ->count(15)
    ->make();

Http::fakeSequence()->push($response);

```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kevin Pijning](https://github.com/kevinpijning)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
