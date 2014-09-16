<?php

namespace common\components;

use Yii;
use yii\web\Cookie;

/**
 *
 * @author Roman Shuplov
 */
class Controller extends \yii\web\Controller {

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        
        // Set the application language if provided by GET, session or cookie
        if (isset($_GET['lang'])) {
            Yii::$app->language = $_GET['lang'];
            Yii::$app->session->set('lang', $_GET['lang']);
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'lang',
                'value' => $_GET['lang'],
                'expire' => time() + (60 * 60 * 24 * 365), // (1 year)
            ]));
        } elseif (Yii::$app->session->has('lang')) {
            Yii::$app->language = Yii::$app->session->get('lang', Yii::$app->language);
        } elseif (Yii::$app->request->cookies->getValue('lang')) {
            Yii::$app->language = Yii::$app->request->cookies->getValue('lang', Yii::$app->language);
        }
        
    }

}
