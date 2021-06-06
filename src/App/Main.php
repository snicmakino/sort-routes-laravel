<?php

namespace App;


class Main
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function execute(): array
    {
        usort($this->routes, function (string $a, string $b) {

            $apath = explode('/', $a);
            $bpath = explode('/', $b);
            if (count($apath) > count($bpath)) {
                return self::check($apath, $bpath);
            }
            return -1 * self::check($bpath, $apath);
        });
        return $this->routes;
    }

    public static function isVariable(string $str): bool
    {
        return $str[0] === "{";
    }

    public static function check($ap, $bp): int
    {
        foreach ($ap as $key => $value) {
            if (count($bp) <= $key) {
                return 1;
            }
            if (self::isVariable($value) && self::isVariable($bp[$key])) {
                continue;
            }
            if ($value === $bp[$key]) {
                continue;
            }
            if (self::isVariable($value)) {
                return 1;
            }
            if (self::isVariable($bp[$key])) {
                return -1;
            }
            // 違う固定値が入っていた時
            return strnatcmp($value, $bp[$key]);
        }
        return 0;
    }
}
