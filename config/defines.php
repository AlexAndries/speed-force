<?php
/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */

define('SPEED_FORCE_USE_SSL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);

require_once('credentials.php');