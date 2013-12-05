yii2-helpers
============

This extension is a collection of useful helper functions for Yii Framework 2.0.

### Html Class
[```VIEW DEMO```](http://demos.krajee.com/helper-functions/html)  

This class extends the [Yii Html Helper](https://github.com/yiisoft/yii2/blob/master/framework/yii/helpers/Html.php) to incorporate additional HTML markup functionality and features available in [Twitter Bootstrap 3.0](http://getbootstrap.com/). The helper functions available in this class are:
- Icon
- Label
- Badge
- Page Header
- Well
- Close Button
- Caret
- Jumbotron
- Abbreviation
- Blockquote
- Address
- List Group
- Panel
- Media
- Media List

### Enum Class
[```VIEW DEMO```](http://demos.krajee.com/helper-functions/enum)  

This class extends the [Yii Inflector Helper](https://github.com/yiisoft/yii2/blob/master/framework/yii/helpers/Inflector.php) with more utility functions for Yii developers. The helper functions available in this class are:
- Is Empty
- Properize
- Time Elapsed
- Format Bytes
- Number to Words
- Year List
- Date List
- Time List
- Boolean List
- IP Address

### Demo
You can see a [demonstration here](http://demos.krajee.com/helpers) on usage of these functions with documentation and examples.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require kartik-v/yii2-helpers "dev-master"
```

or add

```
"kartik-v/yii2-helpers": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

```php
// add this to your code to use these classes
use kartik\helpers\Html;
use kartik\helpers\Enum;

// examples of usage
echo Html::icon('cloud');
echo Enum::properize('Chris');
```

## License

**yii2-helpers** is released under the BSD 3-Clause License. See the bundled `LICENSE` for details.
