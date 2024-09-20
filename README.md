# Factory Generator For Laravel

This library automatically fills the definition() method in the factory based on DB columns

> The library is unlikely to generate all the fields for your model. The entities are very different, 
you will most likely have to edit some fields manually. It is just an attempt to simplify the routine process. 
I will be glad for additions to FakerMapper

## Install

```bash
composer require --dev ercogx/factory-generator-for-laravel
```

## Usage

```bash
php artisan make:generate-factory User
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Ercogx](https://github.com/ercogx)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
