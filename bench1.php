<?php

$basedir = dirname(__DIR__);
$dirs = [];
foreach (scandir($basedir) as $name) {
  if (is_dir($basedir . '/' . $name)) {
    $dirs[$name] = $basedir . '/' . $name;
  }
}

$mode = 'dirit';

$dts = [];
for ($i = 0; $i < 10; ++$i) {
  $t0 = microtime(TRUE);
  for ($j = 0; $j < 10000; ++$j) {
    switch (7) {
      case 0:
        $x = 5;
        break;
      case 1:
        $x = 6;
        break;
      default:
        $x = 7;
        break;
    }
  }
  $t1 = microtime(TRUE);
  $dts[] = ($t1 - $t0) * 1000 * 1000;
}

sort($dts);

print json_encode($dirs, JSON_PRETTY_PRINT) . "\n";
print $mode . "\n";
print json_encode($dts, JSON_PRETTY_PRINT) . "\n";
