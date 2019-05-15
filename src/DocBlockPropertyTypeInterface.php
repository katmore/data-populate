<?php
namespace DataPopulate;

interface DocBlockPropertyTypeInterface {
   public function getDocBlockPropertyTypes(string $property_name): array;
   public function valueMatchesDocBlockPropertyType($value,string $property_name) : bool;
}