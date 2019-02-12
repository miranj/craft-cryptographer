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
use miranj\cryptographer\twigextensions\CryptographerTwigExtension;


class Plugin extends craft\base\Plugin
{
    public $hasCpSettings = true;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Add in our Twig extension
        Craft::$app->view->registerTwigExtension(new CryptographerTwigExtension());
        
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
    
    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        // Get and pre-validate the settings
        $settings = $this->getSettings();
        $settings->validate();
        
        // Get the settings that are being defined by the config file
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle));
        
        return Craft::$app->view->renderTemplate('cryptographer/_settings', [
            'settings' => $this->getSettings(),
            'overrides' => array_keys($overrides),
        ]);
    }
}
