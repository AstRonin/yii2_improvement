<?php

namespace common\components\grid;

use Yii;
use Closure;
use yii\helpers\Html;

/**
 * Description of GridView
 *
 * @author Roman Shuplov
 */
class GridView extends \yii\grid\GridView {

    /**
     * Make two tables for header and body separately if fixedHeader true
     *
     * @var boolean
     */
    public $fixedHeader = false;
    public $fixedHeaderTemplate = '<div>{header}</div><div>{body}</div>';
    /**
     * This options for body if we use separate table
     */
    public $tableOptions2;
    
    /**
     * We can add additional (probably hidden) row
     *
     * @var array
     */
    public $otherRowColumns = [];
    public $otherRowOptions = [];

    public function init() {
        parent::init();
        $this->initOtherRowColumns();
    }
    
    /**
     * 
     * I overrode it for making header and body separately
     * 
     */
    public function renderItems() {
        if (!$this->fixedHeader) {
            return parent::renderItems();
        }
        $content = array_filter([
            $this->renderCaption(),
            $this->renderColumnGroup(),
            $this->showHeader ? $this->renderTableHeader() : false,
        ]);
        $header = Html::tag('table', implode("\n", $content), $this->tableOptions);

        $content = array_filter([
            $this->renderCaption(),
            $this->renderColumnGroup(),
            $this->showFooter ? $this->renderTableFooter() : false,
            $this->renderTableBody(),
        ]);
        $body = Html::tag('table', implode("\n", $content), $this->tableOptions2 ?: $this->tableOptions);

        return strtr($this->fixedHeaderTemplate, [
            '{header}' => $header,
            '{body}' => $body,
        ]);
    }

    /**
     * Use this if we have list of attribute in a header
     * 
     * @see \common\components\grid\DataColumn::renderHeaderCells()
     */
    public function renderTableHeader() {

        $cells = [];
        foreach ($this->columns as $column) {
            /** @var Column $column */
            if (empty($column->secondRow)) {
                $cells[] = $column->renderHeaderCell();
            }
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

        $cells = [];
        foreach ($this->columns as $column) {
            /** @var Column $column */
//            if (!empty($column->secondRow)) {
                if (!empty($column->attributes)) {
                    $cells[] = $column->renderHeaderCells();
                } else {
                    $cells[] = $column->renderHeaderCell();
                }
//            }
        }
        $content .= Html::tag('tr', implode('', $cells), $this->headerRowOptions);

        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }

    /**
     * 
     * We use this if we should add new row.
     * For example:
     * 'afterRow' => ['\common\components\grid\GridView', 'renderTableOtherRow'],
     * 
     * @see \yii\grid\GridView::$afterRow
     * 
     */
    public static function renderTableOtherRow($model, $key, $index, $thisObject) {
        $cells = [];
        /** @var Column $column */
        foreach ($thisObject->otherRowColumns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($thisObject->otherRowOptions instanceof Closure) {
            $options = call_user_func($thisObject->otherRowOptions, $model, $key, $index, $thisObject);
        } else {
            $options = $thisObject->otherRowOptions;
        }
        $options['data-other-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }

    /**
     * If we set common\components\grid\DataColumn::$absent we don't need filter
     */
    public function renderFilters() {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                if (empty($column->absent)) {
                    /** @var Column $column */
                    $cells[] = $column->renderFilterCell();
                }
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }
    
    /**
     * We should use this when we add a new row, because that new row created like other rows
     */
    protected function initOtherRowColumns()
    {
        if (!empty($this->otherRowColumns)) {
            foreach ($this->otherRowColumns as $i => $column) {
                if (is_string($column)) {
                    $column = $this->createDataColumn($column);
                } else {
                    $column = Yii::createObject(array_merge([
                        'class' => $this->dataColumnClass ?: DataColumn::className(),
                        'grid' => $this,
                    ], $column));
                }
                if (!$column->visible) {
                    unset($this->otherRowColumns[$i]);
                    continue;
                }
                $this->otherRowColumns[$i] = $column;
            }
        }
    }

}
