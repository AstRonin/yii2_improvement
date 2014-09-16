<?php

namespace common\components\grid;

/**
 * 
 * Added only $secondRow.
 * We use this if we need two rows in a header
 * 
 */
class SerialColumn extends \yii\grid\SerialColumn {
    
    public $secondRow;
    
}
