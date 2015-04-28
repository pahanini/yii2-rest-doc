<?php

namespace pahanini\restdoc\components;

use phpDocumentor\Reflection\FileReflector;
use Yii;
use pahanini\restdoc\models\ControllerDoc;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;

/**
 * Class Context.
 *
 * @property-read \pahanini\restdoc\models\ControllerDoc[] $controllers
 */
class Context extends \yii\base\Component
{
    /**
     * @var \pahanini\restdoc\models\ControllerDoc[] Keeps controllers.
     */
    private $_controllers = [];

    /**
     * @param $className
     * @param null $objectConfig
     * @throws \yii\base\InvalidConfigException
     */
    private function addControllerDoc($className, $objectConfig = null)
    {
        $controller = Yii::createObject(
            [
                'class' => ControllerDoc::className(),
                'reflection' => new \ReflectionClass($className),
                'objectConfig' => $objectConfig,
            ]
        );
        if ($controller->isValid) {
            $this->_controllers[$controller->path] = $controller;
        } else {
            Yii::error($controller->error, 'restdoc');
        }
    }


    public function addModule($module)
    {
        /* @var $module Module */
        $module = Yii::createObject($module, ['_id', null]);
        $module->setInstance($module);
        $this->addDirs($module->getControllerPath());

        foreach ($module->controllerMap as $value) {
            $this->addControllerDoc(
                isset($value['class']) ? $value['class'] : $value,
                isset($value['modelClass']) ? ['modelClass' => $value['modelClass']] : null
            );
        }
    }

    /**
     * @param $modules
     */
    public function addModules($modules)
    {
        $modules = is_array($modules) ? $modules : [$modules];
        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    /**
     * Adds one or more directories with controllers to context.
     *
     * @param string[] $dirs
     */
    public function addDirs($dirs)
    {
        $dirs = is_array($dirs) ? $dirs : [$dirs];
        foreach ($dirs as $dir) {
            $files = FileHelper::findFiles(Yii::getAlias($dir), [
                'only' => ['*Controller.php']
            ]);
            foreach ($files as $file) {
                $this->addFile($file);
            }
        }
    }

    /**
     * Adds file to context.
     *
     * @param string $fileName
     */
    public function addFile($fileName)
    {
        $reflector = new FileReflector($fileName);
        $reflector->process();

        $classes = $reflector->getClasses();

        if (count($classes) !== 1) {
            throw new InvalidParamException("File $fileName includes more then one class");
        }

        $this->addControllerDoc($classes[0]->getName());
    }

    public function sortControllers($property)
    {
        uasort($this->_controllers, function ($a, $b) use ($property) {
            return strcmp($a->$property, $b->$property);
        });
    }

    public function getControllers()
    {
        return $this->_controllers;
    }
}
