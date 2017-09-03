<?php

namespace adipriyantobpn\composer;


use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package adipriyantobpn\composer
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['composer'] = 'adipriyantobpn\composer\console\ComposerController';
        }
    }

}
