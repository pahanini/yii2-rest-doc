Yii2 Rest Documentator
======================

[![Build Status](https://travis-ci.org/pahanini/yii2-rest-doc.svg?branch=master)](https://travis-ci.org/pahanini/yii2-rest-doc)
[![Latest Stable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/stable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Total Downloads](https://poser.pugx.org/pahanini/yii2-rest-doc/downloads.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Latest Unstable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/unstable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![License](https://poser.pugx.org/pahanini/yii2-rest-doc/license.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc)

Automatic documentator for API build on yii2 rest controllers.

_It's development version!_

Creating of Yii2 controllers is an easy task, but supporting of documentation in actual state is often boring 
and tough challenge. Using automatic tool like phpDocumentator or stagger makes life easier but its still require 
to describe all models fields and rules in tags or comments. 

In other hand Yii2 controllers and models includes a lot of information about internal structure. For example 
field names, scenarios for update and insert operations. This package tires to extract this information from 
rest controllers and models. Using this data along with phpdocumentatorTags package automatically generates 
documentation for API. 

List of data automatically extracted from code:

- controller name
- action's list
- model fields and extra fields (TBD)
- model rules (TBD)

List of tags:

- short and long description of controller
- query tags

Use it to generate index.md for slate or any other documentation file. 
