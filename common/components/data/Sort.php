<?php

namespace common\components\data;

use Yii;
use yii\base\InvalidConfigException;

class Sort extends \yii\data\Sort {

    public $defaultSortAttribute;

    /**
     * Added $defaultSortAttribute
     * 
     * Now we can set default Sorting for each attribute
     * 
     */
    public function createSortParam($attribute) {
        if (!isset($this->attributes[$attribute])) {
            throw new InvalidConfigException("Unknown attribute: $attribute");
        }
        $definition = $this->attributes[$attribute];
        $directions = $this->getAttributeOrders();
        if (isset($directions[$attribute])) {
            $direction = $directions[$attribute] === SORT_DESC ? SORT_ASC : SORT_DESC;
            unset($directions[$attribute]);
        } elseif (isset($definition['default'])) {
            $direction = $definition['default'];
        } elseif ($this->defaultSortAttribute) {
            $direction = $this->defaultSortAttribute;
        } else {
            $direction = SORT_ASC;
        }

        if ($this->enableMultiSort) {
            $directions = array_merge([$attribute => $direction], $directions);
        } else {
            $directions = [$attribute => $direction];
        }

        $sorts = [];
        foreach ($directions as $attribute => $direction) {
            $sorts[] = $direction === SORT_DESC ? '-' . $attribute : $attribute;
        }

        return implode($this->separator, $sorts);
    }

}
