<?php

/**
 * Project: SpeedForce
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

  const REPORTS_TABLE = 'speed_force_reports';

  const RESOURCES_TABLE = 'speed_force_resources';

  const PAGE_INSIGHTS_TABLE = 'speed_force_page_insights';

  const PATH_TO_REPORTS = 'public/reports/';

  public function __construct() {
    register_activation_hook(__FILE__, array($this, 'activateHook'));

    register_deactivation_hook(__FILE__, array($this, 'deactivateHook'));

    register_uninstall_hook(__FILE__, array($this, 'uninstallHook'));

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
    global $wpdb;

    $wpdb->query("
    CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . self::REPORTS_TABLE . "(
      id INT AUTO_INCREMENT PRIMARY KEY,
      report_id VARCHAR(255),
      url TEXT,
      report_url VARCHAR(255),
      page_speed_score VARCHAR(255),
      y_slow_score VARCHAR(255),
      page_bytes VARCHAR(255),
      page_time_load VARCHAR(255),
      page_elements INT,
      date_created DATETIME DEFAULT NOW()
    );
    ");

    $wpdb->query(" 
    CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . self::RESOURCES_TABLE . "(
      id INT AUTO_INCREMENT PRIMARY KEY,
      report_id VARCHAR(255),
      page_speed TEXT,
      y_slow TEXT,
      report_pdf TEXT
    )
    ");

    $wpdb->query("
    CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . self::PAGE_INSIGHTS_TABLE . "(
      id INT AUTO_INCREMENT PRIMARY KEY,
      url TEXT,
      mobile_speed decimal(3, 0),
      desktop_speed decimal(3, 0),
      ui_score decimal(3, 0),
      loading_mobile TEXT,
      loading_desktop TEXT,
      rules_mobile TEXT,
      rules_desktop TEXT,
      rules_ui TEXT,
      date_created DATETIME DEFAULT NOW()
    )
    ");
  }

  public function deactivateHook() {

  }

  public function uninstallHook() {
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . self::RESOURCES_TABLE . "; ");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . self::REPORTS_TABLE . "; ");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . self::PAGE_INSIGHTS_TABLE . "; ");
  }

  public static function getRelativePath() {
    return plugins_url(self::PLUGIN_FOLDER) . '/';
  }
}

require_once('config/defines.php');
new SpeedForce();