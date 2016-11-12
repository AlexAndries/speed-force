<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class SpeedForceAdmin {

  public function __construct() {
    $this->loadVendors();
    //$this->loadGTMetrixFiles();
    //$this->loadPageInsightsFiles();
  }

  private function loadVendors() {
    if (file_exists(SpeedForce::getAbsPath() . 'vendor/autoload.php')) {
      require_once(SpeedForce::getAbsPath() . 'vendor/autoload.php');
    }
  }

  private function loadPageInsightsFiles() {
    require_once(SpeedForce::getAbsPath() . 'admin/google-page-insights/google-auth.php');
    require_once(SpeedForce::getAbsPath() . 'admin/google-page-insights/google-page-insights-result.php');
    require_once(SpeedForce::getAbsPath() . 'admin/google-page-insights/google-page-insights-rules.php');
    require_once(SpeedForce::getAbsPath() . 'admin/google-page-insights/google-page-insights.php');

    $pageInsights = new GooglePageInsights();
  }

  private function loadGTMetrixFiles() {
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-client.php');
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-report.php');
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-wrapper.php');

    $gtmetrix = new GTmetrixWrapper(SPEED_FORCE_GTMETRIX_USER, SPEED_FORCE_GTMETRIX_API, SPEED_FORCE_WEBSITE_HTTP_USER, SPEED_FORCE_WEBSITE_HTTP_PASS);
  }

  public static function convertBytesTo($bytes) {
    if ($bytes / 1048576 > 1) {
      return number_format($bytes / 1048576, 2) . 'MB';
    }

    return number_format($bytes / 1024, 2) . 'KB';
  }

  public static function convertMillisecondsTo($milliseconds) {
    return number_format($milliseconds / 1000, 2) . 's';
  }
}

new SpeedForceAdmin();