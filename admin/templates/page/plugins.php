<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCPlugin;
use TinCan\TCBoardGroup;
use TinCan\TCData;

/**
 * Page template for admin plugin list.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

// TODO Sorting and pagination.
$order = [];

$db = new TCData();

// Load available plugin configuration files.
$plugin_files = opendir(TC_PLUGINS_PATH);

$plugin_configs = [];

if (!empty($plugin_files)) {
    while (false !== ($entry = readdir($plugin_files))) {
        if (!strstr($entry, '.') && is_dir(TC_PLUGINS_PATH.'/'.$entry)) {
            $plugin_path = TC_PLUGINS_PATH.'/'.$entry;
            $config = file_get_contents($plugin_path.'/plugin.json');
            if (!empty($config)) {
                $decoded_config = json_decode($config);
                $decoded_config->path = $entry;
                $plugin_configs[] = $decoded_config;
            }
        }
    }
}

// Load installed plugins.
$plugins = $db->get_indexed_objects(new TCPlugin(), 'plugin_namespace');
?>

<table class="objects">
  <th>Plugin Name</th>
  <th>Version</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($plugin_configs as $config) {
    $plugin = (isset($plugins[$config->namespace])) ? $plugins[$config->namespace] : null;

    $data = [
      [
        'type' => 'text',
        'value' => $config->name,
      ],
      [
        'type' => 'text',
        'value' => $config->version,
      ],
    ];

    if (!empty($plugin)) {
        // Plugin is enabled in the database, so we can use its ID to disable it.
        $data[] = [
          'type' => 'link',
          'url' => '/admin/actions/disable-plugin.php?plugin_id='.$plugin->plugin_id,
          'value' => 'Disable',
        ];
    } else {
        // Plugin isn't enabled, so we use its path in the plugins directory to enable it.
        $data[] = [
          'type' => 'link',
          'url' => '/admin/actions/enable-plugin.php?plugin='.$config->path,
          'value' => 'Enable',
        ];
    }

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>