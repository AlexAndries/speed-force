<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class SpeedForceAdmin {

  public function __construct() {
    $this->loadVendors();
    $this->loadGTmetricsFiles();
  }

  private function loadVendors() {
    if (file_exists(SpeedForce::getAbsPath() . 'vendor/autoload.php')) {
      require_once(SpeedForce::getAbsPath() . 'vendor/autoload.php');
    }
  }

  private function loadGTmetricsFiles() {
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/speed-force-gtmetrix-resources.php');
    require_once(SpeedForce::getAbsPath() . 'admin/gtmetrix/speed-force-gtmetrix.php');

    new SpeedForceGTmetrix(GTMETRIX_USER, GTMETRIX_API);
  }
}

new SpeedForceAdmin();