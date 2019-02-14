<?php

namespace miranj\cryptographer\twigextensions;

use Craft;
use Craft\web\twig\Environment;
use miranj\cryptographer\Plugin;
use Twig_Extension;
use Twig_Filter;
use Twig_Markup;

class CryptographerTwigExtension extends Twig_Extension
{
    public function getName()
    {
        return Craft::t('Cryptographer');
    }
    
    public function getFilters()
    {
        $needs_env = ['needs_environment' => true];
        return [
            new Twig_Filter('maskNumbers', [$this, 'hashidsEncodeFilter']),
            new Twig_Filter('unmaskNumbers', [$this, 'hashidsDecodeFilter']),
            new Twig_Filter('encrypt', [$this, 'encryptFilter']),
            new Twig_Filter('decrypt', [$this, 'decryptFilter']),
            new Twig_Filter('maskLegacy', [$this, 'legacyEncryptFilter'], $needs_env),
            new Twig_Filter('unmaskLegacy', [$this, 'legacyDecryptFilter'], $needs_env),
        ];
    }
    
    /**
     * @param   int     number to be hashid'd
     * @return  string  hashid'd result
     */
    public function hashidsEncodeFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->hashIdsEncode($str);
    }
    
    /**
     * @param   string      hashid to be decoded
     * @return  int|null    number that was originally encoded
     */
    public function hashidsDecodeFilter($str): string
    {
        return implode(',', Plugin::getInstance()->cryptographer->hashIdsDecode((string)$str));
    }
    
    public function encryptFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->encrypt((string)$str);
    }
    
    public function decryptFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->decrypt((string)$str);
    }
    
    
    /**
    * DEPRECATED
    * 
    * Twig wrapper for legacyEncrypt
    */
    public function legacyEncryptFilter(Environment $env, $plaintext, $method='AES-256-CBC', $iv=null)
    {
        $charset = $env->getCharset();
        $data = Plugin::getInstance()->cryptographer->legacyEncrypt($plaintext, $method, $iv);
        return new Twig_Markup($data, $charset);
    }
    
    /**
    * DEPRECATED
    * 
    * Twig wrapper for legacyDecrypt
    */
    public function legacyDecryptFilter(Environment $env, $data, $method='AES-256-CBC')
    {
        $charset = $env->getCharset();
        $plaintext = Plugin::getInstance()->cryptographer->legacyDecrypt($data, $method);
        return new Twig_Markup($plaintext, $charset);
    }
    
}
