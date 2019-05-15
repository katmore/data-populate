<?php
namespace DataPopulate;

use ReflectionProperty;
use ReflectionObject;

/**
 * Trait to facilitate populating an object's properties from input. 
 */
trait MappablePropertiesTrait {
   protected static function protectedPropertiesAreMappable() : bool {
      return true;
   }
   /**
    * @return \ReflectionProperty|null
    */
   protected function mappableProperty2ReflectionProperty(string $map_from_key): ?ReflectionProperty {
      if (($r = new ReflectionObject($this)) && $r->hasProperty($map_from_key)) {
         $p = new ReflectionProperty($this,$map_from_key);
         if (!$p->isStatic() && ($p->isPublic() || (static::protectedPropertiesAreMappable() && $p->isProtected()))) {
            return $p;
         }
      }
      return null;
   }
   public function enumerateMappableKeys(): array {

      return array_reduce(array_filter((new ReflectionObject($this))->getProperties(),function (ReflectionProperty $p) {
         return $this->mappableProperty2ReflectionProperty($p->getName());
      }),function ($carry, ReflectionProperty $p) {
         return array_merge($carry,[
            $p->getName()
         ]);
      },[]);
   }
   public function setPropertyFromKey(string $map_from_key, $value): bool {
      if (!($p = $this->mappableProperty2ReflectionProperty($map_from_key)) instanceof ReflectionProperty) {
         return false;
      }
      if ($this instanceof DocBlockPropertyTypeInterface) {
         if (!$this->valueMatchesDocBlockPropertyType($value,$p->getName())) {
            return false;
         }
      }
      if (($this instanceof MappablePropertiesValidatorInterface) && (!$this->isPropertyValueValid($p->getName(),$value))) {
         return false;
      }
      $this->{$p->getName()} = $value;
      return true;
   }


}