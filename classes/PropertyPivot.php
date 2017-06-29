<?php
namespace TiipiiK\Catalog\Classes;

use October\Rain\Database\Pivot;

/**
 * Property Pivot Model
 */
class PropertyPivot extends Pivot
{

    public function getValueOptions()
    {
        return $this->parent->values_array;
    }

    public function beforeSave()
    {
        tracelog(post());
    }
}
