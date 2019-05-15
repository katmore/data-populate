<?php
namespace DataPopulate;

use stdClass;
use ReflectionProperty;
use ReflectionObject;

/**
 * Trait to facilitate serializing an object to a stdClass, array, or json document.
 */
trait SerializeTrait {
   protected static function protectedPropertiesAreSerialized(): bool {
      return true;
   }
   protected function getSerializableProperty(string $property_name): ?ReflectionProperty {
      if (($r = new ReflectionObject($this)) && $r->hasProperty($property_name)) {
         $p = new ReflectionProperty($this,$property_name);
         if (!$p->isStatic() && ($p->isPublic() || (static::protectedPropertiesAreSerialized() && $p->isProtected()))) {
            return $p;
         }
      }
   }
   protected function enumerateSerializableProperties(): array {
      return array_reduce(array_filter((new ReflectionObject($this))->getProperties(),function (ReflectionProperty $p) {
         return $this->getSerializableProperty($p->getName());
      }),function ($carry, ReflectionProperty $p) {
         return array_merge($carry,[
            $p->getName()
         ]);
      },[]);
   }
   public function toStdClass(): stdClass {
      if (!count($data = array_reduce($this->enumerateSerializableProperties(),function ($carry, string $name) {
         return array_merge($carry,[
            $name => $this->getSerializableProperty($name)
         ]);
      },[]))) {
         return (object) [];
      }
      return json_decode(json_encode($data));
   }
   public function toArray(): array {
      return json_decode(json_encode($this->toStdClass()),true);
   }
   public function jsonSerialize() {
      return $this->toStdClass();
   }
}