<?php
/**
 * @link      https://miranj.in/
 * @copyright Copyright (c) Miranj Design LLP
 */

namespace miranj\cryptographer\models;

use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================
    
    /**
     * @var string|null
     */
    public $hashidsAlphabet = null;
    
    /**
     * @var int|null
     */
    public $hashidsMinLength = 15;
    
    /**
     * @var string|null
     */
    public $secret = null;
    
    
    
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['hashidsAlphabet', 'secret'], 'string'],
            [['hashidsMinLength'], 'numerical'],
        ];
    }
}
