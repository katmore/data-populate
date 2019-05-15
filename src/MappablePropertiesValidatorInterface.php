<?php
namespace DataPopulate;

interface MappablePropertiesValidatorInterface {
   public function isPropertyValueValid(string $property_name,$value) : bool;
}