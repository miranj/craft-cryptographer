<?php

namespace Craft;

use Twig_Extension;  
use Twig_Filter_Method;

class CryptographerTwigExtension extends \Twig_Extension
{
    
    protected $secret = null;
    
    public function __construct()
    {
        $this->secret = craft()->plugins->getPlugin('Cryptographer')->getSettings()->getAttribute('secret');
    }
    
    public function getName()
    {
        return Craft::t('Cryptographer');
    }
    
    public function getFilters()
    {
        return array(
            'encrypt'   => new \Twig_Filter_Method($this, 'encryptFilter'),
            'decrypt'   => new \Twig_Filter_Method($this, 'decryptFilter'),
        );
    }
    
    /**
    * Encrypts text using the given cipher method and
    * initialisation vector. If no intitialisation vector is provided
    * a random value is used for each encryption operation.
    */
    public function encryptFilter($plaintext, $method = 'AES-256-CBC', $iv = null)
    {
        
        $charset = craft()->templates->getTwig()->getCharset();
        
        // Generate an initialisation vector of the required length
        $iv = mb_substr(md5($iv ?: rand()), 0, openssl_cipher_iv_length($method));
        
        // Ecrypt the text
        $cipher = openssl_encrypt($plaintext, $method, $this->secret, 0, $iv);
        
        // Append the initilisation vector before the cipher text
        $data   = $iv.$cipher;
        
        return new \Twig_Markup($data, $charset);
        
    }
    
    
    /**
    * Decrypts data by the given cipher method.
    */
    public function decryptFilter($data, $method = 'AES-256-CBC')
    {
        $charset = craft()->templates->getTwig()->getCharset();
        
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
        
        return new \Twig_Markup($plaintext, $charset);
    }
    
}
