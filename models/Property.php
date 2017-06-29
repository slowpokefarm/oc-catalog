<?php
namespace Tiipiik\Catalog\Models;

use Model;

/**
 * Property Model
 */
class Property extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'tiipiik_catalog_properties';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'values_array',
        'is_used',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'products' => [
            '\Tiipiik\Catalog\Models\Product',
            'table' => 'tiipiik_catalog_prods_props',
            'order' => 'name',
            'pivotModel' => '\TiipiiK\Catalog\Classes\PropertyPivot',
            'pivot' => ['value'],
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getTypeOptions()
    {
        return [
            1 => 'tiipiik.catalog::lang.properties.type_numeric',
            2 => 'tiipiik.catalog::lang.properties.type_string',
            3 => 'tiipiik.catalog::lang.properties.type_dropdown',
        ];
    }

    public function scopeIsUsed($query)
    {
        return $query->where('is_used', true);
    }

    public function getPivotValueOptions()
    {
        return $this->values_array;
    }

    public function getTypeTextAttribute()
    {
        return e(trans($this->getTypeOptions()[$this->type]));
    }

    public function setValuesRepeaterAttribute($values)
    {
        $array = [];

        foreach ($values as $value) {
            $array[$value['id']] = $value['value'];
        }
        $this->attributes['values_array'] = json_encode($array);
    }

    public function getValuesRepeaterAttribute()
    {
        if (isset($this->attributes['values_array'])) {
            $values = json_decode($this->attributes['values_array'], true);
        } else {
            return null;
        }

        $array = [];

        foreach ($values as $key => $value) {
            $array[] = ['id' => $key, 'value' => $value];
        }

        return $array;
    }

    public function getValuesArrayAttribute($values)
    {
        $values = json_decode($values, true);

        return $values;
    }

    public function setValuesArrayAttribute($values)
    {
        $this->attributes['values_array'] = json_encode($values);
    }

    public function filterFields($fields, $context = null)
    {
        $pivotField = 'pivot[value]';

        if (property_exists($fields, $pivotField)) {
            switch ($this->type) {
                case 1:
                    $fields->{$pivotField}->type = 'number';
                    break;
                case 3:
                    $fields->{$pivotField}->type = 'dropdown';
                    $fields->{$pivotField}->options = $this->getPivotValueOptions();
                    break;
                default:
                    $fields->{$pivotField}->type = 'text';
            }
        }

        if (property_exists($fields, 'values_repeater')) {
            if (property_exists($fields, 'type') && $fields->type->value == 3) {
                $fields->values_repeater->hidden = false;
            } else {
                $fields->values_repeater->hidden = true;
            }
        }
    }

    public function beforeSave()
    {
        if ($this->type != 3) {
            $this->values_repeater = [];
        }
    }
}
