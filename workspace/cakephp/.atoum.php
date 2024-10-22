<?php

require __DIR__ .'/vendors/autoload.php';

use atoum\atoum\reports\coverage;
use atoum\atoum\writers\std;

$script->addDefaultReport();

$coverage = new coverage\html();
$coverage->addWriter(new std\out());
$coverage->setOutPutDirectory(__DIR__ . '/coverage');
$runner->addReport($coverage);

$script->enableBranchAndPathCoverage();