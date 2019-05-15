<?php
namespace DataPopulate;

final class DocBlockType {
   /**
    * @var string[][] array with assoc key for every PHP type that matches one or more DocBlock primitive types
    * @link https://docs.phpdoc.org/guides/types.html#primitives
    */
   const PRIMITIVE_DOCBLOCKTYPE = [
      'string' => [
         'string'
      ],
      'integer' => [
         'int',
         'integer'
      ],
      'double' => [
         'float',
         'double'
      ],
      'boolean' => [
         'bool',
         'boolean'
      ],
      'array' => [
         'array'
      ],
      'NULL' => [
         'null'
      ]
   ];
   
   /**
    * Determine if a value is a DocBlock type.
    *
    * @param mixed $value value to check
    * @param string $doc_block_type DocBlock type
    *
    * @return bool true if value type matches the DocBlock type, false otherwise
    */
   public static function valueIsDocBlockType($value, string $doc_block_type): bool {
      
      /*
       * case insensitive type
       */
      $ciType = strtolower($doc_block_type);
      
      /*
       * recursive check if type is "array-of" (i.e. has '[]' suffix)
       */
      if (substr($doc_block_type,-2) === '[]') {
         
         $elemType = substr($doc_block_type,0,-2);
         
         if (!is_array($value)) {
            return false;
         }
         if (!count($value)) {
            return true;
         }
         return !!count(array_filter($value,function ($v) use ($elemType) {
            return static::valueIsDocBlockType($v,$elemType);
         }));
      }
      
      /*
       * check keyword types
       */
      switch ($ciType) {
         case 'mixed' :
            return true;
         case 'void' :
            return false;
         case 'object' :
            return is_object($value);
         case 'false' :
            return $value === false;
         case 'true' :
            return $value === true;
         case 'self' :
         case 'static' :
         case '$this' :
            return is_object($value) && is_a($value,get_called_class());
      }
      
      /*
       * check non-scalar primitive types
       */
      switch ($ciType) {
         case 'resource' :
            return is_resource($value);
         case 'null' :
            return $value === null;
         case 'callable' :
            return is_callable($value);
      }
      
      /**
       * @var string $vt value type
       */
      $vt = gettype($value);
      
      /*
       * check for match on PRIMITIVE_DOCBLOCKTYPE map
       */
      if (isset(DocBlockType::PRIMITIVE_DOCBLOCKTYPE[$vt]) && in_array($ciType,DocBlockType::PRIMITIVE_DOCBLOCKTYPE[$vt],true)) {
         return true;
      }
      
      /*
       * if value is object, check for case sensitive match to type as class name
       */
      return $vt === 'object' && is_a($value,$doc_block_type);
   }
}