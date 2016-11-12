<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class WebPageTestOrgWrapper extends WebPageTestOrgAuth {

  private $report;

  public function __construct($api, $login = array(), $format = 'json') {
    parent::__construct($api, $login, $format);

    $this->report = new WebPageTestOrgReport();
  }

  public function runTest($url) {
    $this->requestTest($url);

    $report = $this->getTestResults();
    while($report['statusCode'] === 100 || $report['statusCode'] === 101){
      sleep(10); //wait to finish the test;

      $report = $this->getTestResults();
    }

    $report = $report['data'];

    $this->report->setReportId($this->reportId)
                 ->setUrl($url)
                 ->setFirstViewData($this->buildRunReport($report['runs'][1]['firstView']))
                 ->setRepeatViewData($this->buildRunReport($report['runs'][1]['repeatView']))
                 ->saveReportToDB();
  }

  private function buildRunReport($data) {
    return array(
      'score_cache'      => $data['score_cache'],
      'score_cdn'        => $data['score_cdn'],
      'score_gzip'       => $data['score_gzip'],
      'score_cookies'    => $data['score_cookies'],
      'score_keep-alive' => $data['score_keep-alive'],
      'score_minify'     => $data['score_minify'],
      'score_combine'    => $data['score_combine'],
      'score_compress'   => $data['score_compress'],
      'score_etags'      => $data['score_etags'],
      'responses_200'    => $data['responses_200'],
      'responses_404'    => $data['responses_404'],
      'responses_other'  => $data['responses_other'],
      'cached'           => $data['cached'],
      'fullyLoaded'      => SpeedForceAdmin::convertMillisecondsTo($data['fullyLoaded']),
      'image_total'      => SpeedForceAdmin::convertBytesTo($data['image_total']),
      'base_page_cdn'    => $data['base_page_cdn']
    );
  }
}