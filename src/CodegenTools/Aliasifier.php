<?php

declare(strict_types = 1);

namespace Donquixote\DID\CodegenTools;

use Donquixote\CodegenTools\Exception\CodegenException;

class Aliasifier {

  /**
   * @var array<string, array<string, string>>
   *   Format: $[$type][$qcn] = $alias.
   */
  private array $aliasesByType = [
    '' => [],
    'function ' => [],
    'const ' => [],
  ];

  /**
   * @var array<string, array<string, string>>
   *   Format: $[$type][$alias] = $qcn.
   */
  private array $importsByType = [
    '' => [],
    'function ' => [],
    'const ' => [],
  ];

  /**
   * @param bool $appendLineBreak
   *   TRUE to append a line break if imports not empty.
   *
   * @return string
   */
  public function getImportsPhp(bool $appendLineBreak = true): string {
    $importsPhp = '';
    foreach ($this->importsByType as $importsForType) {
      sort($importsForType);
      $importsPhp .= implode('', $importsForType);
    }
    if ($appendLineBreak && $importsPhp !== '') {
      $importsPhp .= "\n";
    }
    return $importsPhp;
  }

  /**
   * @param string $php
   *
   * @return static
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public function aliasify(string &$php): static {
    $clone = clone $this;
    $clone->doAliasify($php);
    return $clone;
  }

  /**
   * @param string $php
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function doAliasify(string &$php): void {
    $php_full = '<?php' . "\n" . $php;
    $tokens = token_get_all($php_full);
    # $tokens[] = '#';

    $type = NULL;
    $iLast = -1;
    /** @var array<int, string|null> $fqcnMap */
    $fqcnMap = [];
    foreach ($tokens as $i => $token) {
      switch ($token[0]) {
        case \T_USE:
          // Note: Trait usage is not supported either.
          throw new CodegenException('Cannot aliasify code that already contains imports.');

        case \T_NAMESPACE:
          // Note: Trait usage is not supported either.
          throw new CodegenException('Cannot aliasify code that is already in a namespace.');

        case \T_STRING:
        case \T_NS_SEPARATOR:
        case \T_NAME_QUALIFIED:
          $type = NULL;
          $iLast = -1;
          break;

        case \T_NAME_FULLY_QUALIFIED:
          $fqcnMap[$iLast = $i] = $type;
          $type = NULL;
          break;

        case \T_NEW:
        case \T_INSTANCEOF:
        case ':':
        case '?':
        case \T_ATTRIBUTE:
          $type = '';
          $iLast = -1;
          break;

        case \T_WHITESPACE:
          // Don't change state.
          break;

        case \T_DOUBLE_COLON:
        case \T_VARIABLE:
        case '&':
          $type = NULL;
          $fqcnMap[$iLast] ??= '';
          $iLast = -1;
          break;

        default:
          $type = NULL;
          $iLast = -1;
      }
    }

    unset($fqcnMap[-1]);

    foreach ($fqcnMap as $i => $type) {
      if ($type === NULL) {
        continue;
      }
      $fqcn = $tokens[$i][1];
      $qcn = substr($fqcn, 1);
      $pos = strrpos($qcn, '\\');
      if ($pos === FALSE) {
        // This is a top-level name.
        continue;
      }
      $alias = $this->aliasesByType[$type][$qcn] ?? NULL;
      if ($alias === NULL) {
        $alias = substr($qcn, $pos + 1);
        $import = 'use ' . $type . $qcn;
        if (isset($this->importsByType[$type][$alias])) {
          for ($iAliasVarition = 0; isset($aliasesByType[$type][$alias]); ++$i) {
            $alias = substr($qcn, $pos + 1) . '_' . $iAliasVarition;
          }
          $import .= ' as ' . $alias;
        }
        $this->importsByType[$type][$alias] = $import . ";\n";
        $this->aliasesByType[$type][$qcn] = $alias;
      }
      $tokens[$i][1] = $alias;
    }

    $php = '';
    $n = count($tokens);
    for ($i = 1; $i < $n; ++$i) {
      $token = $tokens[$i];
      $php .= is_array($token) ? $token[1] : $token;
    }
  }

}
