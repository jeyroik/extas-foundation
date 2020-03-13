![PHP Composer](https://github.com/jeyroik/extas-foundation/workflows/PHP%20Composer/badge.svg?branch=master)

# Описание

Данный пакет содержит базовые сущности для Extas`a:

- `extas\components\Item` Базовый объект, позволяющий использовать из коробки плагины и расширения (см. ниже)
- `extas\components\plugins\Plugin` Плагин. Позволяет реализовывать плагины (подробности см. ниже).
- `extas\components\extensions\Extension` Расширение. Позволяет реализовывать расширения (декораторы) для классов, унаследованных от `Item`.

# Требования

- PHP 7.4+
- Какое-либо хранилище (для тестов по-умолчанию используется MongoDb: `jeyroik/extas-repositories-mongo`).

# Установка

`# composer require jeyroik/extas-foundation:2.*`

# Запуск тестов

`# composer run-script test`

# Использование

## Item

Базовую сущность следует использовать как родителя для ваших сущностей (моделей и т.п.).

`class My extends extas\components\Item`

В этом случае вы сразу же получаете:

- поддержку динамических свойств: `$my = new My(); $my->some = 'thing';`
- поддержку интерфейса массива: 
```php
$my = new My();
$my['fieldName'] = 5;
$val = $my['fieldName'];
echo $val;// 5
isset($my['fieldName']); // true
unset($my['fieldName']);
isset($my['fieldName']); // false
```
- поддержку итератора: `foreach($my as $field => $value)`
- поддержку декораторов: `$my->notExistingMethod($arg1, $arg2)`
- поддержку плагинов (событий): внутри метода класса `My`
```php
foreach($this->getPluginsByStage('event.name') as $plugin)
{
    $plugin($arg1, $arg2);
}
```
- встроенные события:
  - создание сущности `<сущность>.created`, срабатывает при сохранении нового экземпляра сущности в хранилище;
  - инициализация сущности `<сущность>.init`, срабатывает при инициализации объекта сущности;
  - удаление объекта сущности `<сущность>.after`, срабатывает при удалении объекта сущности
  - конвертация в массив, строку и целочисленное значение соответственно `<сущность>.to.array`, `<сущность>.to.string`, `<сущность>.to.int`
  
Ниже, после рассмотрения каждой базовой сущности, представлен рабочий пример использования всех сущностей вместе и по отдельности.

## Плагин

Плагин следует использовать как родителя для ваших плагинов.

```php
class MyPlugin extends extas\components\plugins\Plugin {}
```

Чтобы реализовать плагин, вам необходимо перегрузить метод `__invoke()`.
Аргументы метода зависят от конкретной стадии (события). 

Пример реализации плагина можно увидеть ниже.

### Предустановка плагинов

В `extas.json`
```json
{
  "plugins": [
    {
      "class": "class\\Name",
      "stage": "stage.name",
      "priority": 10
    }
  ]
}
```

`priority` - чем выше приоритет, тем раньше (относительно других плагинов на этйо стадии) выполнится плагин. Параметр является необязательным.

## Расширение

Расширение следует использовать как родителя для ваших расширений (декораторов).
Расширение позволяет динамически прозрачно добавлять методы сущностям, не трогая их код.

```php
class MyExtension extends extas\components\Extension implements IMyExtension{}
```

### Предустановка расширений

В `extas.json`

```json
{
  "extensions": [
    {
      "class": "extension\\Class",
      "interface": "extension\\Interface",
      "subject": ["subject.name.1", "subject.name.2"],
      "methods": ["method1", "method2"]
    }
  ]
}
```

### Пример использования каждой сущности по отдельности и вместе

## Item

```php
namespace my\extas;

use extas\components\Item;

class My extends Item
{
    public function getName()
    {
        return $this->config['name'] ?? '';
    }
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return 'my';
    }
}

$my = new my\extas\My(['name' => 'on init']);
echo $my['name']; // 'on init'
echo $my->getName(); // 'on init'
echo $my->name; // 'on init'
$my['description'] = 'Using Item example';

foreach($my as $field => $value) {
    echo $field . ' = ' . $value;
}

/** 
 * Will output:
 * name = on init
 * description = Using Item example
 */
```

## Плагин

```php
namespace my\extas;

use extas\components\Item;

class My extends Item
{
    public function getName()
    {
        $name = $this->config['name'] ?? '';
    
        foreach($this->getPluginsByStage('my.name.get') as $plugin) {
            $plugin($name);
        }
    
        return $name;
    }
    
    public function setName($name)
    {
        $this->config['name'] = $name;
        
        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return 'my';
    }
}

use extas\components\Plugin;

class PluginEmptyName extends Plugin
{
    public function __invoke(&$name)
    {
        $name = $name ?: 'Missed "name"';
    }
} 

$my = new My();
echo $my->getName(); // 'Missed "name"'

```

Внимание! Чтобы вышеуказанный пример сработал, плагин должен быть установлен в системе.
Детали см пакет `jeyroik/extas-installer`.

## Расширение

```php
namespace my\extas;

use extas\components\Item;

class My extends Item
{
    public function getName()
    {
        $name = $this->config['name'] ?? '';
    
        foreach($this->getPluginsByStage('my.name.get') as $plugin) {
            $plugin($name);
        }
    
        return $name;
    }
    
    public function setName($name)
    {
        $this->config['name'] = $name;
        
        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return 'my';
    }
}

/**
 * Для расширений рекомендуется всегда подготавливать интерфейсы.
 * Это помогает при разработке (подсказки) и позволяет удобнее контролировать расширения.
 */
interface IGetMutatedName
{
    /**
     * @return string
     */
    public function getMutatedName(): string;
}

use extas\components\Extension;

class MyGetMutatedName extends Extension implements IGetMutatedName
{
    public string $subject = 'my';

    /**
     * Последним аргументом любого метода, который является расширением,
     * передаётся экземпляр сущности, которая расширяется
     *
     * @param null|My $my
     * 
     * @return string
     */
    public function getMutatedName(My $my = null)
    {
        $name = $my->getName();
        return str_replace('.', '\\', $name);
    }
}

$my = new My(['name' => 'extas.extensions.extension.example']);
echo $my->getMutatedName(); // extas\\extensions\\extension\\example
```

Внимание! Чтобы вышеуказанный пример сработал, расширение должно быть установлено в системе.
Детали см пакет `jeyroik/extas-installer`.
