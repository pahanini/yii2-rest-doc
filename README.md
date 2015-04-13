Yii2 Rest Documentator
======================

[![Build Status](https://travis-ci.org/pahanini/yii2-rest-doc.svg?branch=master)](https://travis-ci.org/pahanini/yii2-rest-doc)
[![Latest Stable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/stable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Total Downloads](https://poser.pugx.org/pahanini/yii2-rest-doc/downloads.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Latest Unstable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/unstable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![License](https://poser.pugx.org/pahanini/yii2-rest-doc/license.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc)

Automatic documentator for API based on yii2 [rest](http://www.yiiframework.com/doc-2.0/guide-rest-quick-start.html) 
controllers.

_It is working but development version!_

Creating of Yii2 controllers is an easy task, but supporting of documentation in actual state is often boring 
and tough challenge. Using automatic tool like [phpDocumentator](https://github.com/phpDocumentor/phpDocumentor2)
or [swagger](http://swagger.io/) makes life easier but its still require to describe all models fields 
and rules using tags or comments. 

In other hand Yii2 controllers and models keep a lot of information about internal structure like actions,  
field names, scenarios for update and insert operations. This package tires to extract this information from 
rest controllers and models. Using this data along with phpdocumentatorTags package automatically generates 
documentation for API controllers. 

List of data automatically extracted from code:

- controller name
- action's list for each controller
- model fields and extra fields (TBD)
- model rules (TBD)

List of tags:

- short and long description of controller
- query tags

Use this tool to generate index.md for [slate](https://github.com/tripit/slate) or any other documentation file. 
