<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GTmetrixWrapper extends GTmetrixClient {

  private $report;

  private $resources = array(
    'pagespeed' => array(),
    'yslow'     => array(),
    'reportPdf' => ''
  );

  public function __construct($username, $apiKey, $websiteUser, $websitePassword) {
    parent::__construct($username, $apiKey, $websiteUser, $websitePassword);

    $this->report = new GTmetrixReport();
  }

  public function runTest($url) {
    $this->report->setTestedUrl($url);
    $data = array(
      'url' => $url
    );

    $report = $this->request('/test', $data);

    while($this->report->getState() != GTmetrixReport::STATE_COMPLETED && $this->report->getState() != GTmetrixReport::STATE_ERROR){
      $this->getScores($report['test_id']);

      sleep(5);
    }
    $this->getResources();
  }

  private function getScores($reportID) {
    $result = $this->request('/test/' . $reportID);

    $this->report->setId($reportID)
                 ->setState($result['state'])
                 ->setError($result['error']);
    if ($this->report->getState() == GTmetrixReport::STATE_COMPLETED) {
      $this->report->setReportUrl($result['results']['report_url'])
                   ->setPagespeedScore($result['results']['pagespeed_score'])
                   ->setYslowScore($result['results']['yslow_score'])
                   ->setHtmlBytes($result['results']['html_bytes'])
                   ->setHtmlLoadTime($result['results']['html_load_time'])
                   ->setPageBytes($result['results']['page_bytes'])
                   ->setPageLoadTime($result['results']['page_load_time'])
                   ->setPageElements($result['results']['page_elements']);

      $this->report->saveReportToDB();

      $this->getResources();
    }
  }

  private function getResources() {
    $this->getPageSpeed();
    $this->getYSlow();
    $this->getReportPDF();

    $this->report->setResources($this->resources);
    $this->report->saveResourcesToDB();
  }

  private function getPageSpeed() {
    $result = $this->request('/test/' . $this->report->getId() . '/pagespeed');

    $this->resources['pagespeed'] = $result['rules'];
  }

  private function getYSlow() {
    $result = $this->request('/test/' . $this->report->getId() . '/yslow');

    $this->resources['yslow'] = $result['g'];
  }

  private function getReportPDF() {
    $result = $this->request('/test/' . $this->report->getId() . '/report-pdf?full=1', array(), false);

    $fp = fopen(SpeedForce::getAbsPath() . SpeedForce::PATH_TO_REPORTS . 'report-' . $this->report->getId() . '.pdf', 'w');
    fwrite($fp, $result);
    fclose($fp);
  }
}