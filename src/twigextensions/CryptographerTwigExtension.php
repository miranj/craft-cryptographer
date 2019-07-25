<?php

namespace miranj\cryptographer\twigextensions;

use Craft;
use craft\web\twig\Environment;
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
            new Twig_Filter('encrypt', [$this, 'encryptFilter']),
            new Twig_Filter('decrypt', [$this, 'decryptFilter']),
            new Twig_Filter('maskNumbers', [$this, 'maskNumbersFilter']),
            new Twig_Filter('unmaskNumbers', [$this, 'unmaskNumbersFilter']),
            new Twig_Filter('maskLegacy', [$this, 'maskLegacyFilter'], $needs_env),
            new Twig_Filter('unmaskLegacy', [$this, 'unmaskLegacyFilter'], $needs_env),
        ];
    }
    
    
    
    /**
     * @param   int     number to be hashid'd
     * @return  string  hashid'd result
     */
    public function maskNumbersFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->maskNumbers($str);
    }
    
    /**
     * @param   string      hashid to be decoded
     * @return  int|null    number that was originally encoded
     */
    public function unmaskNumbersFilter($str): string
    {
        return implode(',', Plugin::getInstance()->cryptographer->unmaskNumbers((string)$str));
    }
    
    /**
     * @param   string      data to be encrypted
     * @return  string      URL-safe cipher text
     */
    public function encryptFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->encrypt((string)$str);
    }
    
    /**
     * @param   string      cipher text to be decrypted
     * @return  string      original data that was encrypted
     */
    public function decryptFilter($str): string
    {
        return Plugin::getInstance()->cryptographer->decrypt((string)$str);
    }
    
    
    
    /**
    * DEPRECATED
    * 
    * Twig wrapper for maskLegacy
    * Previously called encryptFilter in v0.x
    */
    public function maskLegacyFilter(Environment $env, $plaintext, $method='AES-256-CBC', $iv=null)
    {
        $charset = $env->getCharset();
        $data = Plugin::getInstance()->cryptographer->maskLegacy($plaintext, $method, $iv);
        return new Twig_Markup($data, $charset);
    }
    
    /**
    * DEPRECATED
    * 
    * Twig wrapper for unmaskLegacy
    * Previously called decryptFilter in v0.x
    */
    public function unmaskLegacyFilter(Environment $env, $data, $method='AES-256-CBC')
    {
        $charset = $env->getCharset();
        $plaintext = Plugin::getInstance()->cryptographer->unmaskLegacy($data, $method);
        return new Twig_Markup($plaintext, $charset);
    }
    
}
