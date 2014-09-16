<?php

namespace common\components\grid;

use yii\helpers\Html;

/**
 * Description of Column
 *
 * @author Roman Shuplov
 */
class DataColumn extends \yii\grid\DataColumn {

    /**
     * Using two rows in a header
     *
     * @var boolean
     */
    public $secondRow;
    /**
     * Don't use columns
     *
     * @var boolean
     */
    public $absent;
    /**
     * List of attribute in a header if we should insert several columns inside one
     *
     * @var array
     */
    public $attributes;
    /**
     * List of label in a header if we should insert several columns inside one
     *
     * @var type 
     */
    public $labels;

    /**
     * Added $this->absent if we don't need data columns
     * 
     */
    public function renderDataCell($model, $key, $index) {
        if ($this->absent) {
            return '';
        }
        if ($this->contentOptions instanceof Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

    /**
     * We use this method if we have list of attribute in a header
     */
    public function renderHeaderCells() {
        if (!empty($this->attributes)) {
            $h = '';
            foreach ($this->attributes as $key => $value) {
                if (!empty($this->labels)) {
                    if (isset($this->labels[$key])) {
                        $this->label = $this->labels[$key];
                    }
                }
                $this->attribute = $value;
                if ($h) {
                    $h .= ' / ';
                }
                $h .= $this->renderHeaderCellContent();
            }
        }
        
        return Html::tag('th', $h, $this->headerOptions);
    }

}
