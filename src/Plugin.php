<?php
/**
 * Cryptographer plugin for Craft CMS 3.x
 *
 * Adds Twig filters to perform cryptographic operations.
 *
 * @link      https://miranj.in/
 * @copyright Copyright (c) 2018 Miranj
 */

namespace miranj\cryptographer;

use Craft;
use miranj\cryptographer\models\Settings;
use miranj\cryptographer\services\Cryptographer;
use miranj\cryptographer\twigextensions\CryptographerTwigExtension;


class Plugin extends craft\base\Plugin
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Add in our Twig extension
        Craft::$app->view->registerTwigExtension(new CryptographerTwigExtension());
        
        // Set services as components
        $this->set('cryptographer', Cryptographer::class);
        
        Craft::info(
            Craft::t(
                'cryptographer',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
    
    
    
    // Protected Methods
    // =========================================================================
    
    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }
}
