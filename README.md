Cryptographer
=============

A [Craft CMS][craft] plugin that adds [Twig][twig] filters to perform cryptographic operations.

[craft]:http://buildwithcraft.com/
[twig]:http://twig.sensiolabs.org/


Installation
------------

1. [Download][] or clone the repository.
2. Place the `cryptographer` folder inside your `craft/plugins/` folder.
3. Go to Settings > Plugins inside your Control Panel and install **Cryptographer**.
4. [Optional] You can change the `secret` used in cryptographic operations from Settings > Plugins > Cryptographer.

[download]: https://github.com/miranj/craft-cryptographer/archive/master.zip


Usage
-----

### Encryption

```
{{ 'This is a secret text.' | encrypt }}
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

{{ 'hello@example.com' | encrypt | url_encode }}
```

[methods]: http://php.net/manual/en/function.openssl-get-cipher-methods.php


### Decryption

```
{{ '66e46cfa6029c1484jTssikEhQXOk4BvYXWfu1M82R3Ifh1kVxQYmxoGwKc=' | decrypt }}
```

This filter takes one optional argument.

- `$method` — Cipher method to be used. Possible methods can be determined using [`openssl_get_cipher_methods()`][methods]. By default the `AES-256-CBC` method is used.

#### More Examples

```
{{ '9b3c72940c8318b7dGbekO6uMVIAxk7UFA1f0A5tTuoqBo1Vn0jRb3ZjN54=' | decrypt('AES-128-CBC') }}
```
