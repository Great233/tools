<?php

namespace LRU;

interface LRU {

    function set(string $key, $value): void;

    function get(string $key);

    function del($key): void;

    function clear(): void;
}