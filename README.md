Cryptographer
=============

A [Craft CMS][craft] plugin that adds [Twig][twig] filters to perform cryptographic operations.

[craft]:http://buildwithcraft.com/
[twig]:http://twig.sensiolabs.org/



Requirements
------------
This plugin requires Craft CMS 3.0.0 or later. The Craft 2 version is availabe in [the `v0` branch](https://github.com/miranj/craft-cryptographer/tree/v0).



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
    



Usage
-----

### Encryption

```
{{ 'This is a secret text.' | encrypt_legacy }}
```

This filter takes two optional arguments.

- `$method` — Cipher method to be used. Possible methods can be determined using [`openssl_get_cipher_methods()`][methods]. By default the `AES-256-CBC` method is used.
- `$iv` — The initialisation vector. If no initialisation vector is provided, a random value is used every time.

#### Tip

Always URL encode the cipher text (using the built-in [`url_encode`](http://twig.sensiolabs.org/doc/filters/url_encode.html) Twig filter) before using it as a URL component.

#### More Examples

```
{{ 'This is encrypted using the AES-128-CFB method and generates a different cipher each time.' | encrypt('AES-128-CFB') }}

{{ 'This is encrypted using the AES-256-CBC method and generates the same cipher each time.' | encrypt('AES-256-CBC', 'hello') }}

{{ 'hello@example.com' | encrypt_legacy | url_encode }}
```

[methods]: http://php.net/manual/en/function.openssl-get-cipher-methods.php


### Decryption

```
{{ '66e46cfa6029c1484jTssikEhQXOk4BvYXWfu1M82R3Ifh1kVxQYmxoGwKc=' | decrypt_legacy }}
```

This filter takes one optional argument.

- `$method` — Cipher method to be used. Possible methods can be determined using [`openssl_get_cipher_methods()`][methods]. By default the `AES-256-CBC` method is used.

#### More Examples

```
{{ '9b3c72940c8318b7dGbekO6uMVIAxk7UFA1f0A5tTuoqBo1Vn0jRb3ZjN54=' | decrypt_legacy('AES-128-CBC') }}
```
