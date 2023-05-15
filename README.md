# bake-twig

Load plugin in your Application.php

```php
protected function bootstrapCli(): void
{
    ...
    $this->addPlugin('BakeTwig');
    ...
}
```

Baking templates

```bash
./bin/cake BakeTwig.template Articles
```
