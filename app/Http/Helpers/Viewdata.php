<?php 
/* 
 * A utility object for populating a single array for use in the View method. 
 * - instead of creating just a plain ole generic empty class each time. 
 */

 namespace App\Http\Helpers;
 
class Viewdata 
{
    public function __construct() { 


    }

    public function toArray()
    { 
        return (array) $this; 
    }
}