# Fuel Stash

[![Packagist Version](https://img.shields.io/packagist/v/indigophp/fuel-stash.svg?style=flat-square)](https://packagist.org/packages/indigophp/fuel-stash)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/fuel-stash.svg?style=flat-square)](https://packagist.org/packages/indigophp/fuel-stash)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

**This package is a wrapper around [tedivm/stash](https://github.com/tedious/Stash) package, also replaces the core `Cache` class.**


## Install

Via Composer

``` json
{
    "require": {
        "indigophp/fuel-stash": "@stable"
    }
}
```


## Usage

Load the `stash` package before any cache usage. Use `Cache` class as usually.

### Notes

* Cache dependencies are not supported. Check Stash [docs](http://www.stashphp.com/Grouping.html) for similar feature.
* XCache is not supported by Stash.
* Changing driver runtime is not supported. After first using a pool, the driver remains the same.
* Instead of `Cache_Storage_Driver` an instance of `Pool` is returned.
* The stringifying logic is removed for now.

For more details check Stash [docs](http://www.stashphp.com/).


## Contributing

Please see [CONTRIBUTING](https://github.com/indigophp/fuel-stash/blob/develop/CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/fuel-stash/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/indigophp/fuel-stash/blob/develop/LICENSE) for more information.
