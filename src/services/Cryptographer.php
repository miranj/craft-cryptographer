<?php

namespace miranj\cryptographer\services;

use Craft;
use miranj\cryptographer\Plugin;
use yii\base\Component;

/**
* Cryptographer service
* Provides base methods for performing
* encryption and decryption
*/
class Cryptographer extends Component
{
    protected $secret = null;
    
    public function __construct()
    {
        $this->secret = Plugin::getInstance()->settings->secret ?: Craft::$app->config->general->securityKey;
    }
    
    /**
    * DEPRECATED
    * 
    * Encrypts text using the given cipher method and
    * initialisation vector. If no intitialisation vector is provided
    * a random value is used for each encryption operation.
    */
    public function legacyEncrypt($plaintext, $method = 'AES-256-CBC', $iv = null)
    {
        // Generate an initialisation vector of the required length
        $iv = mb_substr(md5($iv ?: rand()), 0, openssl_cipher_iv_length($method));
        
        // Ecrypt the text
        $cipher = openssl_encrypt($plaintext, $method, $this->secret, 0, $iv);
        
        // Append the initilisation vector before the cipher text
        $data = $iv.$cipher;
        
        return $data;
    }
    
    /**
     * DEPRECATED
     * 
     * Decrypts data by the given cipher method.
     */
    public function legacyDecrypt($data, $method = 'AES-256-CBC')
    {
        // Get the length of initialisation vector for give cipher method
        $iv_length = openssl_cipher_iv_length($method);
        
        // Cipher shorter than expected minimum length
        if (strlen($data) <= $iv_length) {
            return false;
        }
        
        // Split the data into initialisation vector and cipher text
        $iv = mb_substr($data, 0, $iv_length);
        
        $cipher = mb_substr($data, $iv_length);
        
        // Decrypt the cipher
        $plaintext = openssl_decrypt($cipher, $method, $this->secret, 0, $iv);
        
        return $plaintext;
    }
}

