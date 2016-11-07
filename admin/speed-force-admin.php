<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class SpeedForceAdmin {

  public function __construct() {
    $this->loadVendors();
    //$this->loadGTMetrixFiles();
  }

  private function loadVendors() {
    if (file_exists(SpeedForce::getAbsPath() . 'vendor/autoload.php')) {
      require_once(SpeedForce::getAbsPath() . 'vendor/autoload.php');
    }
  }

  private function loadGTMetrixFiles() {
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-client.php');
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-report.php');
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/gtmetrix-wrapper.php');

    $gtmetrix = new GTmetrixWrapper(SPEED_FORCE_GTMETRIX_USER, SPEED_FORCE_GTMETRIX_API, SPEED_FORCE_WEBSITE_HTTP_USER, SPEED_FORCE_WEBSITE_HTTP_PASS);
    $gtmetrix->runTest('https://www.google.ro');
  }
}

new SpeedForceAdmin();