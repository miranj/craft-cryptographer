<?php
/**
 * CryptographerTwigExtension
 * 
 * @link      https://miranj.in/
 * @copyright Copyright (c) 2019 Miranj
 */
namespace miranj\cryptographer\twigextensions;

use Craft;
use Craft\web\twig\Environment;
use miranj\cryptographer\Plugin;
use Twig_Extension;
use Twig_Filter;
use Twig_Markup;

class CryptographerTwigExtension extends Twig_Extension
{
    protected $secret = null;
    
    public function __construct()
    {
        $this->secret = Plugin::getInstance()->settings->secret;
    }
    
    public function getName()
    {
        return Craft::t('Cryptographer');
    }
    
    public function getFilters()
    {
        $needs_env = ['needs_environment' => true];
        return [
            new Twig_Filter('encrypt_legacy', [$this, 'legacyEncryptFilter'], $needs_env),
            new Twig_Filter('decrypt_legacy', [$this, 'legacyDecryptFilter'], $needs_env),
        ];
    }
    
    
    /**
    * Encrypts text using the given cipher method and
    * initialisation vector. If no intitialisation vector is provided
    * a random value is used for each encryption operation.
    */
    public function legacyEncryptFilter(Environment $env, $plaintext, $method = 'AES-256-CBC', $iv = null)
    {
        $charset = $env->getCharset();
        
        // Generate an initialisation vector of the required length
        $iv = mb_substr(md5($iv ?: rand()), 0, openssl_cipher_iv_length($method));
        
        // Ecrypt the text
        $cipher = openssl_encrypt($plaintext, $method, $this->secret, 0, $iv);
        
        // Append the initilisation vector before the cipher text
        $data   = $iv.$cipher;
        
        return new Twig_Markup($data, $charset);
    }
    
    
    /**
    * Decrypts data by the given cipher method.
    */
    public function legacyDecryptFilter(Environment $env, $data, $method = 'AES-256-CBC')
    {
        $charset = $env->getCharset();
        
        // Get the length of initialisation vector for give cipher method 
        $iv_length = openssl_cipher_iv_length($method);
        
        // Cipher shorter than expected minimum length
        if(strlen($data) <= $iv_length)
            return false;
        
        // Split the data into initialisation vector and cipher text
        $iv = mb_substr($data, 0, $iv_length);
        
        $cipher = mb_substr($data, $iv_length);
        
        // Decrypt the cipher
        $plaintext = openssl_decrypt($cipher, $method, $this->secret, 0, $iv);
        
        return new Twig_Markup($plaintext, $charset);
    }
    
}
