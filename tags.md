Tags
====

Controller
----------

- `@restdoc-ignore` -  skip controller.
- `@restdoc-label name` -  mark controller with label.
- `@restdoc-query name=false Name of part of name to find users` - query params with description.

Model
-----

Application use model's `fields()` function result to generate fields documents. To describe model's fields you can 
use to approaches.

### Use property tag

If you are already have phpDocumentator `@property` tag you can use it to describe api field. Model's doc block example:

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

If you do not have `@property` tag or api field is directly not connected with property use `@restdoc-field` tag. 
Example:
 
```php
/**
 * @restdoc-field int $id ID
 * @restdoc-field string $title Model's title
 */
```


How to use tags in templates
============================


```php

<?php foreach($this->controllers as $controller): ?>

  <h1><?=$controller->shortDescription?></h1>

  <p><?=$controller->longDescription?></p>
	
  <?php foreach ($controller->model->fields as $field) :?>
    <p><?=$field->name?> (<?=$field->type?>) - <?=$field->description ?></p>
  <?php end foreach; ?>

<?php end foreach; ?>

```
