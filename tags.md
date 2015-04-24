Tags
====

Controller
----------

- `@restdoc-ignore` -  skip controller.
- `@restdoc-label name` -  mark controller with label.
- `@restdoc-query name=false Name of part of name to find users` - query params with description.

Model
-----

Application use model's `fields()` function result to generate fields documents. Field's descriptions can be added 
by using `@restdoc-field` and `@restdoc-link` tags. First one is similar to phpDocumentator `@property` tag.
Second one is for using tag `@property` as a description of field.       

In an example below description and type of property `$name` will be used for field `name`. Also description and type
of `$age` property will be used for `person_age` field. 

```php
/**
 * @property string $name Name property description
 * @property int $age Age property description
 * @restdoc-field int $id ID
 * @restdoc-field string $title Model's title
 * @restdoc-link name $name
 * @restdoc-link age $person_age
 */
```

How to use this tags in templates.

```php

<?php foreach($this->controllers as $controller): ?>

  <h1><?=$controller->shortDescription?></h1>

  <p><?=$controller->longDescription?></p>
	
  <?php foreach ($controller->model->fields as $field) :?>
    <p><?=$field->name?> (<?=$field->type?>) - <?=$field->description ?></p>
  <?php end foreach; ?>

<?php end foreach; ?>

```
