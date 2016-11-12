<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 * @documentation: https://sites.google.com/a/webpagetest.org/docs/advanced-features/raw-test-results
 */
class WebPageTestOrgReport {

  private $reportId;

  private $url;

  private $firstViewData = array();

  private $repeatViewData = array();

  /**
   * @param mixed $reportId
   *
   * @return WebPageTestOrgReport
   */
  public function setReportId($reportId) {
    $this->reportId = $reportId;

    return $this;
  }

  /**
   * @param mixed $url
   *
   * @return WebPageTestOrgReport
   */
  public function setUrl($url) {
    $this->url = $url;

    return $this;
  }

  /**
   * @param array $firstViewData
   *
   * @return WebPageTestOrgReport
   */
  public function setFirstViewData($firstViewData) {
    $this->firstViewData = $firstViewData;

    return $this;
  }

  /**
   * @param array $repeatViewData
   *
   * @return WebPageTestOrgReport
   */
  public function setRepeatViewData($repeatViewData) {
    $this->repeatViewData = $repeatViewData;

    return $this;
  }

  public function saveReportToDB() {
    global $wpdb;

    $wpdb->insert($wpdb->prefix . SpeedForce::WEB_PAGE_TEST_ORG_TABLE, array(
      'url'         => $this->url,
      'report_id'   => $this->reportId,
      'first_view'  => json_encode($this->firstViewData),
      'repeat_view' => json_encode($this->repeatViewData)
    ));
  }

}