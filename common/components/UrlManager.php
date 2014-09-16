<?php

namespace common\components;

use Yii;

class UrlManager extends \yii\web\UrlManager {

    public function createUrl($params) {
        if (empty($params['lang'])) {
            if (Yii::$app->session->has('lang')) {
                Yii::$app->language = Yii::$app->session->get('lang', Yii::$app->language);
            } elseif (Yii::$app->request->cookies->getValue('lang')) {
                Yii::$app->language = Yii::$app->request->cookies->getValue('lang');
            }
            $params['lang'] = Yii::$app->language;
        }
        return parent::createUrl($params);
    }

}
