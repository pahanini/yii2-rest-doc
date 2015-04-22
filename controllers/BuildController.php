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
     * @var string[]|string One ore more directories with controllers.
     */
    public $sourceDir;

    /**
     * @var File to write result. If empty controller will output the result.
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
        $context->addDirs($this->sourceDir);
        $result = $this->renderPartial(Yii::getAlias($this->template), ['controllers' => $context->controllers]);
        if ($this->targetFile) {
            file_put_contents($this->targetFile, $result);
        } else {
            echo $result;
        }
    }
}
