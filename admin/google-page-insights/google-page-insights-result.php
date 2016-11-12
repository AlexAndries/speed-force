<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GooglePageInsightsResult {

  private $mobileSpeed;

  private $ui;

  private $desktopSpeed;

  private $loading = array();

  private $rules = array();

  public function __construct() {
    $this->rules = array(
      'mobile'  => array(),
      'desktop' => array(),
      'ui'      => array()
    );
  }

  /**
   * @param $name
   * @param $data
   * @param $type
   *
   * @return GooglePageInsightsResult
   */
  public function setRule($name, $data, $type) {
    $this->rules[$type][$name] = $data;

    return $this;
  }

  public function getResult() {
    return array(
      'mobileSpeed'  => $this->mobileSpeed,
      'ui'           => $this->ui,
      'desktopSpeed' => $this->desktopSpeed,
      'loading'      => $this->loading,
      'rules'        => $this->rules
    );
  }

  /**
   * @return mixed
   */
  public function getMobileSpeed() {
    return $this->mobileSpeed;
  }

  /**
   * @param mixed $mobileSpeed
   *
   * @return GooglePageInsightsResult
   */
  public function setMobileSpeed($mobileSpeed) {
    $this->mobileSpeed = $mobileSpeed;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getUi() {
    return $this->ui;
  }

  /**
   * @param mixed $ui
   *
   * @return GooglePageInsightsResult
   */
  public function setUi($ui) {
    $this->ui = $ui;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getDesktopSpeed() {
    return $this->desktopSpeed;
  }

  /**
   * @param mixed $desktopSpeed
   *
   * @return GooglePageInsightsResult
   */
  public function setDesktopSpeed($desktopSpeed) {
    $this->desktopSpeed = $desktopSpeed;

    return $this;
  }

  /**
   * @param $type
   *
   * @return array mixed
   */
  public function getLoading($type) {
    return $this->loading[$type];
  }

  /**
   * @param array $loading
   * @param $type
   */
  public function setLoading($loading, $type) {
    $this->loading[$type] = $loading;
  }

  /**
   * @param $type
   *
   * @return array mixed
   */
  public function getRules($type) {
    return $this->rules[$type];
  }
}