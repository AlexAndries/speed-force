<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 * Plugin Name: SpeedForce
 * Plugin URI: http://speed-plugin.wordpress.dev/
 * Description: Descriptions todo: write descriptions
 * Version: 0.0.1
 * Author: Alex Andries
 * Author URI: https://github.com/djkarui
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: speed-force
 *
 * SpeedForce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * SpeedForce is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SpeedForce. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */
class SpeedForce {

  const VERSION = '0.0.1';

  const PLUGIN_NAME = 'SpeedForce';

  const PLUGIN_FOLDER = 'speed-force';

  public function __construct() {
    register_activation_hook(__FILE__, array(&$this, 'activateHook'));

    register_deactivation_hook(__FILE__, array(&$this, 'deactivateHook'));

    register_uninstall_hook(__FILE__, array(&$this, 'uninstallHook'));

    $this->registerAdminClass();
    $this->registerPublicClass();
  }

  private function registerAdminClass() {
    if (is_admin()) {
      require_once(self::getAbsPath() . 'admin/speed-force-admin.php');
    }
  }

  private function registerPublicClass() {
    if (!is_admin()) {
      require_once(self::getAbsPath() . 'public/speed-force-public.php');
    }
  }

  public static function getAbsPath() {
    return dirname(__FILE__) . '/';
  }

  public function activateHook() {

  }

  public function deactivateHook() {

  }

  public function uninstallHook() {

  }

  public static function getRelativePath() {
    return plugins_url(self::PLUGIN_FOLDER) . '/';
  }
}

require_once('config/defines.php');
new SpeedForce();