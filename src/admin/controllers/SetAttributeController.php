<?php

namespace luya\estore\admin\controllers;

/**
 * Set Attribute Controller.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 */
class SetAttributeController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\estore\models\SetAttribute';

    public function actionJsonObject()
    {
        return $this->render('json-object');
    }

    public function actionJsonObjectArray()
    {
        return $this->render('json-object-array');
    }
}
