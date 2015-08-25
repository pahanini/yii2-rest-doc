<?php

namespace pahanini\restdoc\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;
use pahanini\restdoc\components\Context;

/**
 * Builds REST documentation.
 */
class BuildController extends Controller
{
    /**
     * @var string Default action to run.
     */
    public $defaultAction = 'run';

    /**
     * @var string `\pahanini\restdoc\models\ControllerDoc` property used for for sorting.
     */
    public $sortProperty = 'shortDescription';

    /**
     * @var string[]|string One ore more directories with controllers. You can use aliases.
     */
    public $sourceDirs;

    /**
     * @var string[]|string One ore more directories with controllers.
     */
    public $sourceModules;

    /**
     * @var File to write result. If empty controller will output the result. You can use file alias.
     */
    public $targetFile;

    /**
     * @var string Template file name.
     */
    public $template;

    /**
     * Run builder.
     */
    public function actionRun()
    {
        $context = new Context();
        if ($this->sourceDirs) {
            $context->addDirs($this->sourceDirs);
        }
        if ($this->sourceModules) {
            $context->addModules($this->sourceModules);
        }
        if ($this->sortProperty) {
            $context->sortControllers($this->sortProperty);
        }
        $result = $this->renderPartial(Yii::getAlias($this->template), ['controllers' => $context->controllers]);
        if ($this->targetFile) {
            file_put_contents(Yii::getAlias($this->targetFile), $result);
        } else {
            echo $result;
        }
    }
}
