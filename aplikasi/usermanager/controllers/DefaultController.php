<?php

namespace app\usermanager\controllers;

use frontend\modules\usermanager\models\Accessrole;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `usermanager` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'index', ],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Accessrole::getAccess($action, $rule);
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
