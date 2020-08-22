<?php

namespace LRU;

use InvalidArgumentException;

class LRUCache implements LRU {

    private $keys = [];

    private $store = [];

    private $size;

    public function setSize(int $size) {
        $this->size = $size;
    }

    private function contains(string $key) {
        return isset($this->keys[$key]);
    }

    private function outOfRange() {
        return count($this->keys) > $this->size;
    }

    public function set(string $key, $value): void {
        if ($this->size <= 0) {
            throw new InvalidArgumentException('You must set cache size first.');
        }
        if ($this->contains($key)) {
            $this->moveToFront($key);
            return;
        }
        $this->pushToFront($key);
        $this->store[$key] = $value;
        if ($this->outOfRange()) {
            $this->eliminate();
        }
    }

    private function pushToFront(string $key): void {
        array_unshift($this->keys, $key);
    }

    private function moveToFront(string $key): void {
        $index = array_search($key, $this->keys);
        array_splice($this->keys, $index, 1);
        $this->pushToFront($key);
    }

    public function get($key) {
        $this->moveToFront($key);
        return $this->keys[$key] ?? null;
    }

    private function eliminate() {
        $outcasts = array_splice($this->keys, $this->size - 1);
        $this->del($outcasts);
    }

    public function del($key) {
        if (!is_array($key)) {
            $key = [$key];
        }

        foreach ($key as $item) {
            if (($index = array_search($this->keys, $item)) !== false) {
                array_splice($this->keys, $index, 1);
            }
            if (array_key_exists($item, $this->store[$item])) {
                unset($this->store[$item]);
            }
        }
    }

    public function clear() {
        $this->keys = [];
        $this->store = [];
    }
}