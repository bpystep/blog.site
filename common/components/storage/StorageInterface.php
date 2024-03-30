<?php

namespace common\components\storage;

interface StorageInterface
{
    public function save(string $filePath, string $name, array $options = []): bool;

    public function delete(string $name): bool;

    public function fileExists(string $name): bool;

    public function getUrl(string $name): string;

    public function getLocalPath(string $path): string;
}
