<?php
namespace DataPopulate;

use ReflectionObject;
use ReflectionProperty;

trait UsingInputTrait {

   /**
    * Populates this object instance using values from an array.
    *
    * @param array $input
    */
   public function usingArray(array $input): self {
      $fromKeys = [];
      if ($this instanceof MappablePropertiesInterface) {
         $fromKeys = array_filter($input,function ($v, $k) {
            return $this->setPropertyFromKey($k,$v);
         },ARRAY_FILTER_USE_BOTH);
      }

      if ($this instanceof MappingAwareInterface) {
         $this->mappingComplete($fromKeys);
      }
   }

   /**
    * Populates this object instance using values from another object.
    *
    * @param object $input
    *
    * @return void
    */
   public function usingObject(object $input): self {
      $fromKeys = [];
      if ($this instanceof MappablePropertiesInterface) {
         $fromKeys = array_filter((new ReflectionObject($input))->getProperties(ReflectionProperty::IS_PUBLIC),function (ReflectionProperty $p) {
            return $this->setPropertyFromKey($p->getName(),$p->getValue());
         });
      }

      if ($this instanceof MappingAwareInterface) {
         $this->mappingComplete($fromKeys);
      }
   }
}