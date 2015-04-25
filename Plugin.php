<?php namespace Riuson\EveIGB;

use System\Classes\PluginBase;

/**
 * EveIGB Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'EveIGB',
            'description' => 'Some methods for EVE Online in-game browser',
            'author'      => 'Riuson',
            'icon'        => 'icon-leaf'
        ];
    }

}
