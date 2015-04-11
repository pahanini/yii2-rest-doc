Yii2 Rest Documentator
======================

[![Build Status](https://travis-ci.org/pahanini/yii2-rest-doc.svg?branch=master)](https://travis-ci.org/pahanini/yii2-rest-doc)
[![Latest Stable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/stable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Total Downloads](https://poser.pugx.org/pahanini/yii2-rest-doc/downloads.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Latest Unstable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/unstable.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![License](https://poser.pugx.org/pahanini/yii2-rest-doc/license.svg)](https://packagist.org/packages/pahanini/yii2-rest-doc)

Automatic rest api documentator for yii2 rest controllers.

_It's development version!_

Creating of Yii2 controllers is easy task, but supporting one's documentation in actual state is often is boring 
and tough challenge. 

In other hand Yii2 controllers and models includes a lot of information about internal structure 
to automatically generate documentation. E.g. field names, scenarios for update and insert operations. 

The main background idea of this package is to generate documentation not only used on phpDoc tags, but based at 
Yii2 rest controllers and connected models internal structure.
 
This extension use phpDoc tags, controllers methods. connected models, one's rules and  scenarios. 

 You do not need to describe each field and action.Documentation fuses your code and phpDoc tags to generate excellent 
 documentation. It's basically generates index.md for slate but you can generate any other file. 
  
 