<?php
namespace DataPopulate;

/**
 * Interface for populating an object's data from input.
 */
interface UsingInputInterface {
   
   public function usingArray(array $input) : object;
   public function usingObject(object $input) : object;
}
