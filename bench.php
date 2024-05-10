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
  switch ($mode) {
    case 'scandir':
      foreach ($dirs as $dir) {
        foreach (scandir($dir) as $name) {
          # is_file($dir . '/' . $name);

          # is_dir($dir . '/' . $name);
          # preg_match('@^\w+\.php$@', $name);
        }
      }
      break;

    case 'dirit':
      foreach ($dirs as $dir) {
        foreach (new DirectoryIterator($dir) as $file) {
          # $file->isFile();
          # $file->isDir();
        }
      }
      break;
  }
  $t1 = microtime(TRUE);
  $dts[] = ($t1 - $t0) * 1000 * 1000;
}

sort($dts);

print json_encode($dirs, JSON_PRETTY_PRINT) . "\n";
print $mode . "\n";
print json_encode($dts, JSON_PRETTY_PRINT) . "\n";
