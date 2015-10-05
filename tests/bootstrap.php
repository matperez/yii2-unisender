<?php
/**
 * Created by PhpStorm.
 * Filename: bootstrap.php
 * User: andrey
 * Date: 05.10.15
 * Time: 21:15
 */
require_once(__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../vendor/yiisoft/yii2/Yii.php');

Yii::setAlias('@tests', __DIR__);