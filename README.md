![PHP Composer](https://github.com/jeyroik/extas-foundation/workflows/PHP%20Composer/badge.svg?branch=master)
![codecov.io](https://codecov.io/gh/jeyroik/extas-foundation/coverage.svg?branch=master)
<a href="https://codeclimate.com/github/jeyroik/extas-foundation/maintainability"><img src="https://api.codeclimate.com/v1/badges/ec6cc3b52b11b9b3a453/maintainability" /></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-foundation/v)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-foundation/downloads)](//packagist.org/packages/jeyroik/extas-jsonrpc)
[![Dependents](https://poser.pugx.org/jeyroik/extas-foundation/dependents)](//packagist.org/packages/jeyroik/extas-jsonrpc)

# Описание

Данный пакет содержит базовые сущности для Extas`a:

- `extas\components\Item` Базовый объект, позволяющий использовать из коробки плагины и расширения (см. ниже)
- `extas\components\plugins\Plugin` Плагин. Позволяет реализовывать плагины (подробности см. ниже).
- `extas\components\extensions\Extension` Расширение. Позволяет реализовывать расширения (декораторы) для классов, унаследованных от `Item`.

# Требования

- PHP 7.4+
- Какое-либо хранилище (для тестов по-умолчанию используется JSON-файл).

# Установка пакета

`# composer require jeyroik/extas-foundation:6.*`

# Установка extas-совместимого приложения

`# vendor/bin/extas install -t "repo/template/path" -s "repo/classes/save/path"`

Для сущностей доступна стадия `extas\\interfaces\\stages\\IStageBeforeInstallEntity`, подключившись к которой можно проводить дополнительные манипуляции с данными сущности.

Сушности приложения устанавливаются раньше сущностей библиотек.

`# vendor/bin/extas-extra e`

Подробнее про команду `extra` читайте ниже.

# Установка прочих сущностей

Для установки прочих сущностей, доступна команда `extra`.
С помощью плагинов есть возможность подключиться к данной команде и через единый интерфейс этой команды устаналивать любые сущности и вообще выполнять любые действия, которые требуется.

Чтобы посмотреть список доступных опций, используйте помощь по команде:

`# vendor/bin/extas extra -h`

# Конфигурация приложения

- Для настройки конфигурации вашего приложения, необходимо создать два файла
  -  `extas.app.storage.json` для кофигурации хранилища, плагинов и расширений;
  -  `extas.app.json` для конфигурации сущностей.
  - Минимальную конфигурацию можно найти в 
    - `extas.app.storage.dist.json`, а также в текущей документации ниже по тексту.
    - `extas.app.dist.json`, а также в текущей документации ниже по тексту.
- Если вы разрабатываете бибилиотеку, то настройки для неё необходимо складывать в 
  - `extas.storage.json` - конфигурация хранилища, плагинов и расширений.
  - `extas.json` - конфигурация сущностей.
  - Минимальную конфигурацию можно найти в
    - `extas.storage.dist.json`, а также в текущей документации ниже по тексту.
    - `extas.dist.json`, а также в текущей документации ниже по тексту.

# Запуск тестов

`# composer test`

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
- поддержку быстрого доступа к хранилищу: `$my->myTable()->one(...)`
- поддержку плагинов (событий): внутри метода класса `My`
  - создание сущности `<сущность>.created`, срабатывает при сохранении нового экземпляра сущности в хранилище;
  - инициализация сущности `<сущность>.init`, срабатывает при инициализации объекта сущности;
  - удаление объекта сущности `<сущность>.after`, срабатывает при удалении объекта сущности
  - конвертация в массив, строку и целочисленное значение соответственно `<сущность>.to.array`, `<сущность>.to.string`, `<сущность>.to.int`
- поддержку целого ряда вспомогательных методов (каждый из которых предоставляет соответствующее событие для плагинов) вида
  - __toArray()
  - __toJson()
  - __toInt()
  - __equal(IItem $other) - сравнение с другой сущностью.
  - __has('attr1', 'attr2', ...) - проверка наличия необходимых атрибутов.
  - __select('attr1', 'attr2', ...) - получение сущности с ограниченным набором полей.
- поддержку создания новых событий для плагинов:
```php
foreach($this->getPluginsByStage('event.name') as $plugin)
{
    $plugin($arg1, $arg2);
}
```

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

В `extas.app.storage.json` для приложения и в `extas.storage.json` для библиотек:
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

`priority` - чем выше приоритет, тем раньше (относительно других плагинов на этой стадии) выполнится плагин. Параметр является необязательным.

## Расширение

Расширение следует использовать как родителя для ваших расширений (декораторов).
Расширение позволяет динамически прозрачно добавлять методы сущностям, не трогая их код.

```php
class MyExtension extends extas\components\Extension implements IMyExtension{}
```

### Предустановка расширений

В `extas.app.storage.json` для приложения и в `extas.storage.json` для библиотек:

```json
{
  "extensions": [
    {
      "class": "extension\\Class",
      "interface": "extension\\Interface",
      "subject": ["subject.name.1", "subject.name.2", "*"],
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

// extas.storage.json/extas.app.storage.json
{
    "plugins": [
        {
            "class": "my\\extas\\PluginEmptyName",
            "stage": "my.name.get"
        }
    ]
}

// somewhere in a code

$my = new My();
echo $my->getName(); // 'Missed "name"'

```

Внимание! Чтобы вышеуказанный пример сработал, плагин должен быть установлен в системе.
Детали см. в разделе `Установка`.

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

// extas.storage.json/extas.app.storage.json
{
    "extensions": [
        {
            "class": "my\\extas\\MyGetMutatedName",
            "interface": "my\\extas\\IGetMutatedName",
            "subject": ["my"],
            "methods": ["getMutatedName"]
        }
    ]
}

// somewhere in a code

$my = new My(['name' => 'extas.extensions.extension.example']);
echo $my->getMutatedName(); // extas\\extensions\\extension\\example
```

Внимание! Чтобы вышеуказанный пример сработал, расширение должно быть установлено в системе.
Детали см. в разделе `Установка`.

# extas.app.storage.json

```json
{
    "name": "vendor/package",
    "drivers": [
        {
            "driver": "\\driver\\Class",
            "options": {
                "dsn": "{username}:{userpassword}@{host}:{port}/{db} | {path/to/db}",
                "username": "",
                "password": "",
                "host": "",
                "port": "",
                "db": ""
            },
            "tables": ["t1", ...]
        }
    ],
    "tables": {
        "some_entities": {
            "item_class": "",
            "pk": "",
            "aliases": ["alias1", ...],
            "hooks": {
                "create-before": true,
                "update-before": true,
                "update-after": true,
                ...
            },
            "code": {
                "create-before": "echo 'any code you want'; \\extas\\components\\repositories\\RepoItem::throwIfExist($this, $item, ['name'])",
                ...
            }
        }, 
        ...
    },
    "plugins": [
        {
            ...
        },
        ...
    ],
    "extensions": [
        {
            ...
        },
        ...
    ],
    "envs": [
        "name1": "description"
    ]
}
```

# extas.app.json

Сушности приложения устанавливаются раньше сущностей библиотек.

```json
{
    "some_entities": [
        {
            ...
        },
        ...
    ]
}
```

# extas.storage.json

`Внимание`: не размещайте в данной конфигурации секцию с настройкой драйверов - она будет игнорироваться.
Драйвера настраиваются в `extas.app.storage.json`.

```json
{
    "name": "vendor/package",
    "tables": {
        "some_entities": {
            "item_class": "",
            "pk": "",
            "aliases": ["alias1", ...],
            "hooks": {
                "create-before": true,
                "update-before": true,
                "update-after": true,
                ...
            },
            "code": {
                "create-before": "echo 'any code you want';",
                ...
            }
        }, 
        ...
    },
    "plugins": [
        {
            ...
        },
        ...
    ],
    "extensions": [
        {
            ...
        },
        ...
    ],
    "envs": {
        "name1": "description 1"
    }
}
```

# extas.json

```json
{
    "some_entities": [
        {
            ...
        },
        ...
    ]
}
```

# ENV

Чтобы узнать какие требуются переменные окружения, выполните команду `env`:

```bash
# vendor/bin/extas env
```

Чтобы добавить свои переменные окружения в данный список, добавьте в конфигурации хранилища `extas.app.storage.json` (для библиотек в `extas.storage.json`) раздел `envs` и опишите свои переменные как это показано в примерах соответствующих конфигураций выше.