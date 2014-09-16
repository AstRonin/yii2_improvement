<?php

namespace common\components;

use yii\widgets\PjaxAsset;
use yii\helpers\Json;

/**
 * Added 'basePoint'
 * 
 * We should set new $basePoint instead of 'document' for jQuery selectors
 * when we use Modal of Bootstrap with remote data.
 * For example '.modal-dialog'
 * Don't forget to write:
 * $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
})
 * for clean remote data
 * 
 * @author Roman Shuplov
 */
class Pjax extends \yii\widgets\Pjax {
    
    public $basePoint = 'document';
    
    public function registerClientScript() {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        $options = Json::encode($this->clientOptions);
        $linkSelector = Json::encode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');
        $formSelector = Json::encode($this->formSelector !== null ? $this->formSelector : '#' . $id . ' form[data-pjax]');
        $basePoint = ($this->basePoint === 'document') ? $this->basePoint : Json::encode($this->basePoint);
        $view = $this->getView();
        PjaxAsset::register($view);
        
        $js = "jQuery($basePoint).off('click', $linkSelector);";
        $js .= "jQuery($basePoint).pjax($linkSelector, \"#$id\", $options);";
        $js .= "\njQuery($basePoint).off('submit', $formSelector);";
        $js .= "\njQuery($basePoint).on('submit', $formSelector, function (event) {jQuery.pjax.submit(event, '#$id', $options);});";
        $view->registerJs($js);
    }

    
}
