<?php
namespace DataPopulate;

use ReflectionObject;
use ReflectionProperty;
use ReflectionClass;
use stdClass;
use phpDocumentor\Reflection\DocBlockFactory;

trait ArrayAccessTrait {
   use MappablePropertiesTrait;
   public function offsetExists($offset): bool {
      return is_string($offset) && ($this->propIsProperty($offset) || isset($this->otherProp[$offset]));
   }
   public function offsetGet($offset) {
      return is_string($offset)?$this->get($offset) : null;
   }
   public function offsetSet($offset, $value): void {
      if (!is_string($offset))
            return;
         $this->set($offset,$value);
   }
   public function offsetUnset($offset): void {
      if (!is_string($offset))
            return;
         if ($this->propIsProperty($offset)) {
            if ($this->propToReflectionProperty($offset) instanceof ReflectionProperty) {
               $this->$offset = null;
               return;
            }
            unset($this->$offset);
            return;
         }
         unset($this->otherProp[$offset]);
   }
}