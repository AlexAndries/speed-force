<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GTmetrixReport {

  const STATE_QUEUED = 'queued';
  const STATE_STARTED = 'started';
  const STATE_COMPLETED = 'completed';
  const STATE_ERROR = 'error';

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $state;

  /**
   * @var string
   */
  protected $error;

  /**
   * @var string
   */
  protected $reportUrl;

  /**
   * @var int
   */
  protected $pagespeedScore;

  /**
   * @var int
   */
  protected $yslowScore;

  /**
   * @var int
   */
  protected $htmlBytes;

  /**
   * @var int
   */
  protected $htmlLoadTime;

  /**
   * @var int
   */
  protected $pageBytes;

  /**
   * @var int
   */
  protected $pageLoadTime;

  /**
   * @var int
   */
  protected $pageElements;

  /**
   * @var string
   */
  protected $testedUrl;

  /**
   * @var array
   */
  protected $resources = array();

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   *
   * @return GTmetrixReport
   */
  public function setId($id) {
    $this->id = $id;

    return $this;
  }

  /**
   * @return string
   */
  public function getState() {
    return $this->state;
  }

  /**
   * @param string $state
   *
   * @return GTmetrixReport
   */
  public function setState($state) {
    $this->state = $state;

    return $this;
  }

  /**
   * @return string
   */
  public function getError() {
    return $this->error;
  }

  /**
   * @param string $error
   *
   * @return GTmetrixReport
   */
  public function setError($error) {
    $this->error = $error;

    return $this;
  }

  /**
   * @return string
   */
  public function getReportUrl() {
    return $this->reportUrl;
  }

  /**
   * @param string $reportUrl
   *
   * @return GTmetrixReport
   */
  public function setReportUrl($reportUrl) {
    $this->reportUrl = $reportUrl;

    return $this;
  }

  /**
   * @return int
   */
  public function getPagespeedScore() {
    return $this->pagespeedScore;
  }

  /**
   * @param int $pagespeedScore
   *
   * @return GTmetrixReport
   */
  public function setPagespeedScore($pagespeedScore) {
    $this->pagespeedScore = $pagespeedScore;

    return $this;
  }

  /**
   * @return int
   */
  public function getYslowScore() {
    return $this->yslowScore;
  }

  /**
   * @param int $yslowScore
   *
   * @return GTmetrixReport
   */
  public function setYslowScore($yslowScore) {
    $this->yslowScore = $yslowScore;

    return $this;
  }

  /**
   * @return int
   */
  public function getHtmlBytes() {
    return $this->htmlBytes;
  }

  /**
   * @param int $htmlBytes
   *
   * @return GTmetrixReport
   */
  public function setHtmlBytes($htmlBytes) {
    $this->htmlBytes = $htmlBytes;

    return $this;
  }

  /**
   * @return int
   */
  public function getHtmlLoadTime() {
    return $this->htmlLoadTime;
  }

  /**
   * @param int $htmlLoadTime
   *
   * @return GTmetrixReport
   */
  public function setHtmlLoadTime($htmlLoadTime) {
    $this->htmlLoadTime = $htmlLoadTime;

    return $this;
  }

  /**
   * @return int
   */
  public function getPageBytes() {
    return $this->pageBytes;
  }

  /**
   * @param int $pageBytes
   *
   * @return GTmetrixReport
   */
  public function setPageBytes($pageBytes) {
    $this->pageBytes = $pageBytes;

    return $this;
  }

  /**
   * @return int
   */
  public function getPageLoadTime() {
    return $this->pageLoadTime;
  }

  /**
   * @param int $pageLoadTime
   *
   * @return GTmetrixReport
   */
  public function setPageLoadTime($pageLoadTime) {
    $this->pageLoadTime = $pageLoadTime;

    return $this;
  }

  /**
   * @return int
   */
  public function getPageElements() {
    return $this->pageElements;
  }

  /**
   * @param int $pageElements
   *
   * @return GTmetrixReport
   */
  public function setPageElements($pageElements) {
    $this->pageElements = $pageElements;

    return $this;
  }

  /**
   * @return string
   */
  public function getTestedUrl() {
    return $this->testedUrl;
  }

  /**
   * @param string $testedUrl
   *
   * @return GTmetrixReport
   */
  public function setTestedUrl($testedUrl) {
    $this->testedUrl = $testedUrl;

    return $this;
  }

  /**
   * @return array
   */
  public function getResources() {
    return $this->resources;
  }

  /**
   * @param array $resources
   *
   * @return GTmetrixReport
   */
  public function setResources($resources) {
    $this->resources = $resources;

    return $this;
  }

  public function saveReportToDB() {
    global $wpdb;

    $wpdb->insert($wpdb->prefix . SpeedForce::REPORTS_TABLE, array(
      'report_id'        => $this->id,
      'url'              => $this->testedUrl,
      'report_url'       => $this->reportUrl,
      'page_speed_score' => $this->pagespeedScore,
      'y_slow_score'     => $this->yslowScore,
      'page_bytes'       => $this->convertBytesTo($this->pageBytes),
      'page_time_load'   => $this->convertMillisecondsTo($this->pageLoadTime),
      'page_elements'    => $this->pageElements
    ));
  }

  private function convertBytesTo($bytes) {
    if ($bytes / 1048576 > 1) {
      return number_format($bytes / 1048576, 2) . 'MB';
    }

    return number_format($bytes / 1024, 2) . 'KB';
  }

  private function convertMillisecondsTo($milliseconds) {
    return number_format($milliseconds / 1000, 2) . 's';
  }

  public function saveResourcesToDB() {
    global $wpdb;

    $wpdb->insert($wpdb->prefix . SpeedForce::RESOURCES_TABLE, array(
      'report_id'  => $this->id,
      'page_speed' => json_encode($this->resources['pagespeed']),
      'y_slow'     => json_encode($this->resources['yslow']),
      'report_pdf' => SpeedForce::getRelativePath() . SpeedForce::PATH_TO_REPORTS . 'report-' . $this->id . '.pdf'
    ));
  }
}