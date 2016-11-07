<?php

/**
 * Project: speed-force
 * User: Alex Andries <alexandru.andries@outlook.com>
 */

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class SpeedForceGTmetrix {

  private $client;

  private $shortReport = array();

  private $resourcesClient;

  public function __construct($username, $apikey) {
    $this->client = new GTMetrixClient();
    $this->client->setUsername($username);
    $this->client->setAPIKey($apikey);

    $this->resourcesClient = new SpeedForceGTmetrixResources($this->client);
  }

  public function getSpeedTest($url) {
    $test = $this->client->startTest($url);

    while($test->getState() != GTMetrixTest::STATE_COMPLETED && $test->getState() != GTMetrixTest::STATE_ERROR){
      $results = $this->client->getTestStatus($test);
      sleep(5);
    }
    if (isset($results) && is_a($results, 'GTMetrixTest') && $results->getState() == GTMetrixTest::STATE_COMPLETED) {
      $resources = $results->getResources();
      $this->shortReport = array(
        'reportUrl'      => $results->getReportUrl(),
        'pagespeedScore' => $results->getPagespeedScore(),
        'yslowScore'     => $results->getYslowScore(),
        'htmlBytes'      => $results->getHtmlBytes(),
        'htmlLoadTime'   => $results->getHtmlLoadTime(),
        'pageBytes'      => $results->getPageBytes(),
        'pageLoadTime'   => $results->getPageLoadTime(),
        'pageElements'   => $results->getPageElements(),
        'fullReport'     => $resources['report_pdf_full']
      );

      $this->resourcesClient->setPageSpeedReportUrl($resources['pagespeed']);
      $this->resourcesClient->setYSlowReportUrl($resources['yslow']);
    } else {
      var_dump('is not...');
    }
  }
}