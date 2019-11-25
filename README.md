<img align="right" src="./src/icon.svg" width="100" height="100" alt="Cryptographer icon">

Cryptographer
=============

A [Craft CMS][] 3 plugin that adds [Twig][] filters to encrypt and decrypt content via URL-safe strings. Useful for situations such as:

- Generating single-use URLs, such as for private pages
- Masking numeric IDs like 521 as strings, as in [`youtube.com/watch?v=dQw4w9WgXcQ`][yt]
- Generating URLs for users or entries without revealing their usernames, slugs or IDs

[craft cms]:https://craftcms.com/
[twig]:https://twig.symfony.com/
[yt]:https://youtube.com/watch?v=dQw4w9WgXcQ



Contents
--------
- [Usage](#usage)
  - [Secure Encryption](#secure-encryption)
  - [Masking Numbers](#masking-numbers)
  - [Legacy Methods](#legacy-methods)
- [Configuration](#configuration)
- [Installation](#installation)
- [Requirements](#requirements)
- [Changelog](./CHANGELOG.md)
- [Contributors](https://github.com/miranj/craft-cryptographer/graphs/contributors)
- [License](./LICENSE)



Usage
-----

Cryptographer offers two broad options to transform data into ciphertext, and back from ciphertext to original data. One is cryptographically secure and based on Craft's [security component][cs1] (which is in turn [based on Yii's][ys1]). The other is _not secure_ and based on the popular [Hashids][] library. Both options produce URL-safe strings.

[hashids]:https://hashids.org/php/
[cs1]:https://docs.craftcms.com/api/v3/craft-services-security.html
[ys1]:https://www.yiiframework.com/doc/guide/2.0/en/security-cryptography



### Secure Encryption

Use this method to encrypt sensitive information. It differs from the native [`encenc`][enc] filter in that the output is always a URL-safe string. Note that the cipher generated each time will be different. So this is good for generating single use tokens, but not a good candidate for generating permanent URLs.

[enc]:https://docs.craftcms.com/v3/dev/filters.html#encenc

#### Templating
```twig
{{ 'Sensitive data' | encrypt }}
{{ 'Y3J5cHQ64Q8YoiXmSUYq6c2mcg6YjmVjNTBkNGViNmE4M2FiNTVmYTdkZTUyYjJhZmNjNzY5NWRiNDc5M2ExNzRhZTE1ZWZmMjU2NzFkMDNhMzEyZWIX9Rj4f4vOKB2XCljjXha3aKfJw4c6D-T7zMoXhKpeFw=='
   | decrypt }}
```

#### API
```php
$ciphertext = \miranj\cryptographer\Plugin::getInstance()->cryptographer->encrypt('Sensitive data');
$originaltext = \miranj\cryptographer\Plugin::getInstance()->cryptographer->decrypt($ciphertext);
```



### Masking Numbers

_Important: This method should not be considered cryptographically secure. Avoid using it for encoding sensitive data like passwords._

This method converts numbers like 457 into strings like `'qan8deK8'`. Use this method to mask numbers such as element IDs (or a list of IDs) for use inside URLs. It is a wrapper for the popular [Hashids][] library.

#### Templating
```twig
{{ user.id | maskNumbers }}
{{ 'qan8deK8' | unmaskNumbers }}
```

#### API
```php
$mask = \miranj\cryptographer\Plugin::getInstance()->cryptographer->maskNumbers([521]);
$numbers = \miranj\cryptographer\Plugin::getInstance()->cryptographer->unmaskNumbers('qan8deK8');
```



### Legacy Methods

_Important: This method should not be considered cryptographically secure. Avoid using it for encoding sensitive data like passwords._

Cryptographer provides a third method of masking and unmasking strings which is deprecated in v1.x. It is offerred purely for backwards compatibility of sites that were using the Craft 2 version of this plugin (v0.x) and have content that needs to be converted back to the original. 

#### `maskLegacy`

```twig
{{ 'Do not use for sensitive data' | maskLegacy }}
{{ 'Do not use for sensitive data' | maskLegacy | url_encode }}
{{ 'Do not use for sensitive data' | maskLegacy('AES-128-CFB') }}
```

This filter takes two optional arguments.

- `$method` — Cipher method to be used. Possible methods can be determined using [`openssl_get_cipher_methods()`][methods]. By default the `AES-256-CBC` method is used.
- `$iv` — The initialisation vector. If no initialisation vector is provided, a random value is used every time.

[methods]: http://php.net/manual/en/function.openssl-get-cipher-methods.php

#### `unmaskLegacy`

```twig
{{ '66e46cfa6029c1484jTssikEhQXOk4BvYXWfu1M82R3Ifh1kVxQYmxoGwKc=' | unmaskLegacy }}
{{ '9b3c72940c8318b7dGbekO6uMVIAxk7UFA1f0A5tTuoqBo1Vn0jRb3ZjN54=' | unmaskLegacy('AES-128-CBC') }}
```

This filter takes one optional argument.

- `$method` — Cipher method to be used. Possible methods can be determined using [`openssl_get_cipher_methods()`][methods]. By default the `AES-256-CBC` method is used.



Configuration
-------------

To configue Cryptographer, create a `cryptographer.php` file in your [`config/`][config] folder, which returns an array. This file supports Craft's standard [multi-environment configurations][multi].

[config]:https://docs.craftcms.com/v3/config/
[multi]:https://docs.craftcms.com/v3/config/environments.html#multi-environment-configs

Here is a sample config file along with a list of all possible settings and their default values:

```php
<?php

return [
    // hashids
    'hashidsMinLength' => 15,   // pad output to fit at least a certain length
    'hashidsAlphabet' => null,  // use a custom alphabet, eg: 'abcdefghijklmnopqrstuvwxyz'
    
    // legacy
    'secret' => null,           // defaults to using Craft's securityKey
];
```

-  `hashidsMinLength` – Configure Hashids to ensure the output is of a certain length. The resultant string may not be exactly of this length, but will not be shorter than this number.

-  `hashidsAlphabet` – Use a custom alphabet to generate the output string. The default alphabet used by Hashids is `'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'`.

-  `secret` – A private, cryptographically-secure key that is used for encrypting and decrypting data. Default value is `null`, in which case the plugin uses [`securityKey`](sk) from Craft's general config.
   
   This setting is retained for legacy reasons only, i.e. if you are migrating a Craft 2 website. It should only be explicity set when you need to decrypt ciphers generated by the Craft 2 version of this plugin. Copy over the value from _Settings > Plugins > Cryptographer > Secret_ before migrating from Craft 2.

[sk]:https://docs.craftcms.com/v3/config/config-settings.html#securitykey



Installation
------------

You can install this plugin from the [Plugin Store][ps] or with Composer.

[ps]:https://plugins.craftcms.com/cryptographer

#### From the Plugin Store
Go to the Plugin Store in your project’s Control Panel and search for “Cryptographer”.
Then click on the “Install” button in its modal window.

#### Using Composer
Open your terminal and run the following commands:

    # go to the project directory
    cd /path/to/project
    
    # tell composer to use the plugin
    composer require miranj/craft-cryptographer
    
    # tell Craft to install the plugin
    ./craft install/plugin cryptographer
    



Requirements
------------
This plugin requires Craft CMS 3.0.0 or later. The Craft 2 version is availabe in [the `v0` branch](https://github.com/miranj/craft-cryptographer/tree/v0).



---

Brought to you by [Miranj](https://miranj.in/)
