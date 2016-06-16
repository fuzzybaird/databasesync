<?php namespace Fuzzybaird\DatabaseSync;
use Config;
use Backend;
use System\Classes\PluginBase;

/**
 * DatabaseSync Plugin Information File
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
            'name'        => 'DatabaseSync',
            'description' => 'No description provided yet...',
            'author'      => 'Fuzzybaird',
            'icon'        => 'icon-leaf'
        ];
    }
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'DatabaseSync Settings',
                'description' => 'Manage Sites to be in sync',
                'category'    => 'Database',
                'icon'        => 'icon-cog',
                'class'       => 'Fuzzybaird\DatabaseSync\Models\Settings',
                'order'       => 500,
                'keywords'    => 'Database Sync Backup',
            ]
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Fuzzybaird\DatabaseSync\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'fuzzybaird.databasesync.some_permission' => [
                'tab' => 'DatabaseSync',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'databasesync' => [
                'label'       => 'DatabaseSync',
                'url'         => Backend::url('fuzzybaird/databasesync/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['fuzzybaird.databasesync.*'],
                'order'       => 500,
            ],
        ];
    }

    public function boot(){
        $options  = [
            'driver' => 'local',
            'root'   => __DIR__.'/tmp',
        ];
        Config::set('filesystems.disks.fuzzybaird_databasesync', $options);
        // dd(Config::get('filesystems.disks'));
    }

}
