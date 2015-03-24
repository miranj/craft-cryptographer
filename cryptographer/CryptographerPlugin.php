<?php
namespace Craft;

class CryptographerPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Cryptographer');
    }
    
    function getVersion()
    {
        return '0.1';
    }
    
    function getDeveloper()
    {
        return 'Miranj';
    }

    function getDeveloperUrl()
    {
        return 'http://miranj.in';
    }
    
    protected function defineSettings()
    {
        return array(
            'secret'    => array(AttributeType::String, 'default' => md5(rand())),
        );
    }
    
    public function getSettingsHtml()
    {
        return craft()->templates->render('cryptographer/_settings', array(
            'settings' => $this->getSettings(),
        ));
    }
    
    public function addTwigExtension()
    {
        Craft::import('plugins.cryptographer.twigextensions.CryptographerTwigExtension');
        return new CryptographerTwigExtension();
    }
}
