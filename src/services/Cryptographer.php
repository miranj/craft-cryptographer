<?php

namespace miranj\cryptographer\services;

use Craft;
use craft\helpers\StringHelper;
use Hashids\Hashids;
use miranj\cryptographer\Plugin;
use yii\base\Component;

/**
* Cryptographer service
* Provides base methods for performing
* encryption and decryption
*/
class Cryptographer extends Component
{
    private $_prefix = 'crypt:';
    private $_hashIds = null;
    protected $secret = null;
    
    public function __construct()
    {
        $this->secret = Plugin::getInstance()->settings->secret ?: Craft::$app->config->general->securityKey;
    }
    
    
    
    // ======================
    // = Hashids (insecure) =
    // ======================
    
    public function getHashIds()
    {
        if ($this->_hashIds === null) {
            $hashidsAlphabet = Plugin::getInstance()->settings->hashidsAlphabet;
            $hashidsMinLength = Plugin::getInstance()->settings->hashidsMinLength;
            $this->_hashIds = $hashidsAlphabet === null
                ? new Hashids($this->secret, $hashidsMinLength)
                : new Hashids($this->secret, $hashidsMinLength, $hashidsAlphabet);
        }
        return $this->_hashIds;
    }
    
    /**
     * Generate a URL safe hash by encoding numbers
     * 
     * @param   mixed   number(s) to be hashed 1 | "12" | [1, 2]
     * @return  string  URL safe hashed string [A-Za-z0-9]
     */
    public function maskNumbers($number): string
    {
        return $this->getHashIds()->encode($number);
    }
    
    /**
     * Decode a Hashid'd string
     * 
     * @param   string  URL safe hashed string [A-Za-z0-9]
     * @return  [int]   list of decoded numbers [1] | [12] | [1, 2]
     */
    public function unmaskNumbers(string $str): array
    {
        $number = $this->getHashIds()->decode($str);
        return $number;
    }
    
    
    
    // =====================
    // = Secure Encryption =
    // =====================
    
    /**
     * URL safe encryption of the passed string
     */
    public function encrypt(string $str): string
    {
        $code = $this->_prefix.Craft::$app->getSecurity()->encryptByKey($str);
        $code = StringHelper::base64UrlEncode($code);
        return $code;
    }
    
    /**
     * Decrypts URL safe strings encoded by `encrypt`
     */
    public function decrypt(string $code): string
    {
        $str = StringHelper::base64UrlDecode($code);
        if (strncmp($str, $this->_prefix, strlen($this->_prefix)) === 0) {
            $str = Craft::$app->getSecurity()->decryptByKey(substr($str, strlen($this->_prefix)));
        }
        return $str;
    }
    
    
    
    // ====================================
    // = DEPRECATED Encryption (insecure) =
    // ====================================
    
    /**
    * DEPRECATED
    * 
    * Masks text using the given cipher method and
    * initialisation vector. If no intitialisation vector is provided
    * a random value is used for each encryption operation.
    */
    public function maskLegacy($plaintext, $method = 'AES-256-CBC', $iv = null)
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
     * Unmasks data using the given cipher method.
     */
    public function unmaskLegacy($data, $method = 'AES-256-CBC')
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
