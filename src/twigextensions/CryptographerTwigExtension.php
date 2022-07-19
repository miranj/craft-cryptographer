<?php

namespace miranj\cryptographer\twigextensions;

use Craft;
use craft\web\twig\Environment;
use miranj\cryptographer\Plugin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Markup;

class CryptographerTwigExtension extends AbstractExtension
{
    public function getName()
    {
        return Craft::t('Cryptographer');
    }
    
    public function getFilters()
    {
        $needs_env = ['needs_environment' => true];
        return [
            new TwigFilter('encrypt', [$this, 'encryptFilter']),
            new TwigFilter('decrypt', [$this, 'decryptFilter']),
            new TwigFilter('maskNumbers', [$this, 'maskNumbersFilter']),
            new TwigFilter('unmaskNumbers', [$this, 'unmaskNumbersFilter']),
            new TwigFilter('maskLegacy', [$this, 'maskLegacyFilter'], $needs_env),
            new TwigFilter('unmaskLegacy', [$this, 'unmaskLegacyFilter'], $needs_env),
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
        return new Markup($data, $charset);
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
        return new Markup($plaintext, $charset);
    }
    
}
