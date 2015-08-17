#Yii2 Rest Controller Documentator

[![Build Status](https://travis-ci.org/pahanini/yii2-rest-doc.svg?branch=master)](https://travis-ci.org/pahanini/yii2-rest-doc)
[![Latest Stable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/stable)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Total Downloads](https://poser.pugx.org/pahanini/yii2-rest-doc/downloads)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![Latest Unstable Version](https://poser.pugx.org/pahanini/yii2-rest-doc/v/unstable)](https://packagist.org/packages/pahanini/yii2-rest-doc) 
[![License](https://poser.pugx.org/pahanini/yii2-rest-doc/license)](https://packagist.org/packages/pahanini/yii2-rest-doc)

## About

Create precise documentation to your Yii2 API [REST](http://www.yiiframework.com/doc-2.0/guide-rest-quick-start.html) 
controllers. Library parses your code and generates objects with meta data that could be used with any template 
engine to generate great API docs.

You do not need to edit documentation when you change you code. Just rebuild you docs with this tool.

## Install

- Add `"pahanini/yii2-rest-doc": "*"` to required section of your composer.json  
- Add to your console application config

``` php

'controllerMap' => [
	'build-rest-doc' => [
		'sourceDirs' => [
			'@frontend\controllers\rest',   // <-- path to your API controllers
		],
		'template' => '//restdoc/restdoc.twig', 
		'class' => '\pahanini\restdoc\controllers\BuildController',
		'targetFile' => 'path/to/nice-documentation.html'
	],
]
```

## Template example (twig)

``` html 
{% for controller in controllers %}
	<h2>{{ controller.shortDescription }}</h2>
	<p>{{ controller.longDescription }}</p>
	{% if controller.hasLabel('authenticated') %}
		<div class="warning">Require login and password!</div>
	{% endif %}
	<p>List of supported actions:
		<ul>
			{% for action in controller.actions %}
				<li>{{ action }}</li>
			{% endfor %}
		</ul>
	</p>
	<p>Get params available for index action:</p>
		<ul>
			{% for item in controller.query %}
				<li>
					<b>item.variableName</b> - {{ item.description }}, default - {{ item.defaultValue }}
				</li>
			{% endfor %}
		</ul>
	</p>
	<p>Model fields:</p>
	<table>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Description</th>
			<th>Can be updated?</th>
		</tr>
		{% for item in controller.model.fields %}
			<tr>
				<td>{{ item.name }}</td>
				<td>{{ item.type }}</td>
				<td>{{ item.description }}</td>
				<td>{{ item.isInScenario('api-update')  ? 'yes' : 'no' }}</td>
			</tr>
		{% endfor %}
	</table>
{% endfor %}
```

## Data available in template  

List of data automatically extracted from code:

- controller name
- action's of each controller
- model fields 
- extra fields
- model rules (TBD)

List of special tags:

- short and long description of controller
- query tags

Inheritance is also supported. Use `@inherited` or `@inheritdoc` tags.

### Controller
   
- `@restdoc-ignore` -  skip controller.
- `@restdoc-label name` -  mark controller with label. Label name available via `controller.hasLabel('labelName')` in template
- `@restdoc-query name=false Name of part of name to find users` - query params with description.

### Model

To describe model's fields you can use two approaches. 

#### Link to property tag.

If you already have phpDocumentator `@property` tag you can use it to describe API field. 
Model's doc block example:

```php

/**
 * My model.
 *
 * @property string $title Model's title
 */
```

* `@restdoc-link $title` - use `$title` property to describe `$title` api field   
* `@restdoc-link title $model_title` - use `$title` property to describe `$model_title` api field

### Separate field description

If you do not have `@property` tag or API field is not directly connected with property use `@restdoc-field` tag.
 
Example:
 
```php

/**
 * @restdoc-field int $id ID
 * @restdoc-field string $title Model's title
 */
```

### Extra fields

Use @restdoc-extraField and @restdoc-extraLink for extra fields.

## Integrate With Slate

[Slate](https://github.com/tripit/slate) is probably one of the best tools to generate nice API. So you can 
use this tool to create index.md file for slate. You can use on afterAction event to automatically start slate.

Example:

``` php
'controllerMap' => [
	'build-rest-doc' => [
		'sourceDirs' => [
			'@frontend\controllers\rest',
		],
		'template' => '//restdoc/restdoc.twig',
		'class' => '\pahanini\restdoc\controllers\BuildController',
		'targetFile' => 'path/to/slate/index.md',
		'on afterAction' => function() { exec("bundle exec middleman build") }
	],
]
```
  
## Rationale

Creating of Yii2 controllers is an easy task, but supporting of documentation in actual state is often boring 
and tough challenge. Using automatic tool like [phpDocumentator](https://github.com/phpDocumentor/phpDocumentor2)
or [swagger](http://swagger.io/) makes life easier but its still require to describe all models fields 
and rules using tags or comments. 

In other hand Yii2 controllers and models keep a lot of information about internal structure like actions,  
field names, scenarios for update and insert operations. This package extracts such an information from 
REST controllers and models and using this data along with phpdocumentator tags automatically generates 
index.md for [slate](https://github.com/tripit/slate) or any other documentation file. 

