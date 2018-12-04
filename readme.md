# ClientInfo

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/fynduck/client-info.svg?style=flat-square)](https://packagist.org/packages/fynduck/client-info)

## Install
`composer require fynduck/client-info`

## Usage
Add to your class

```
class NameClass
{
    use ClientInfo;
    
    .........
 
```
Get client platform: `$this->getPlatform(request()->header('User-Agent'))`

Get client platform version: `$this->getPlatformVersion(request()->header('User-Agent'))`

Get client browser name: `$this->getBrowserName(request()->header('User-Agent'))`

Get client browser version: `$this->getBrowserVersion(request()->header('User-Agent'), 'ub')` (ub browser get from method `getBrowserName`)

Get client refer domain: `$this->getDomainReferer(request()->header('referer'))`

> **Note:** all method have default value from request()

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security-related issues, please email DummyAuthorEmail instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
