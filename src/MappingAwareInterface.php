<?php
namespace DataPopulate;

interface MappingAwareInterface {
   /**
    * Invoked after the mapping of this object has completed.
    * @return void
    */
   public function mappingComplete(array $from_keys) : void;
}
