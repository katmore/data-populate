<?php
namespace DataPopulate;

interface MappablePropertiesInterface {
   public function enumerateMappableKeys(): array;
   public function setPropertyFromKey(string $map_from_key, $value): bool;
}