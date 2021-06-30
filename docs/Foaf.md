# Foaf

## Example
```php
use VkLib\VkFoaf;

$subjectId = 1; //Pavel Durov

$foaf = new VkFoaf($subjectId);

$foaf->execute($throws);
var_dump($foaf->getFoaf());
```