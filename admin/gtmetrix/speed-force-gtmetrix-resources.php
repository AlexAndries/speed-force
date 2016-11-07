<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class SpeedForceGTmetrixResources {

  private $pageSpeedResults = array();

  private $ySlowResults = array();

  private $client;

  private $pageSpeedReportUrl;

  private $ySlowReportUrl;

  public function __construct($client) {
    $this->client = $client;
  }

  /**
   * @param mixed $pageSpeedReportUrl
   *
   * @return SpeedForceGTmetrixResources
   */
  public function setPageSpeedReportUrl($pageSpeedReportUrl) {
    $this->pageSpeedReportUrl = $pageSpeedReportUrl;

    return $this;
  }

  /**
   * @param mixed $ySlowReportUrl
   *
   * @return SpeedForceGTmetrixResources
   */
  public function setYSlowReportUrl($ySlowReportUrl) {
    $this->ySlowReportUrl = $ySlowReportUrl;

    return $this;
  }

  /**
   * @return array
   */
  public function getPageSpeedResults() {
    $this->runPageSpeedReport();

    return $this->pageSpeedResults;
  }

  private function runPageSpeedReport() {

  }

  /**
   * @return array
   */
  public function getYSlowResults() {
    $this->runYSlowReport();

    return $this->ySlowResults;
  }

  private function runYSlowReport() {

  }

}