<?php
namespace DataPopulate;

use ReflectionClass;
use phpDocumentor\Reflection\DocBlockFactory;

trait DocBlockPropertyTypeTrait {
   
   /**
    * @return string[]
    */
   public function getDocBlockPropertyTypes(string $property_name): array {
      if ((new ReflectionClass($this))->hasProperty($property_name)) {
         $p = ReflectionProperty($this,$property_name);
         if (!empty($c = $p->getDocComment())) {
            if (count($type = array_unique(array_reduce(DocBlockFactory::createInstance()->create($c)->getTagsByName('var'),function ($carry, $item) {
               return array_merge($carry,explode('|',current(explode(' ',$item->__toString()))));
            },[])))) {
               return $type;
            }
         }
      }
      return [
               'mixed'
      ];
   }

   public function valueMatchesDocBlockPropertyType($value,string $property_name) : bool {
      return !!count(array_filter($this->getDocBlockPropertyTypes($property_name),function ($type) use ($value) {
         return DocBlockType::valueIsDocBlockType($value,$type);
      }));
   }
}