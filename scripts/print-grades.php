<?php

use JohnsHopkins\Color\Grades;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$grades = new Grades(true);
$grades->printTable();
