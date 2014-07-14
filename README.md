yii2-helpers
============

This extension is a collection of useful helper functions for Yii Framework 2.0.

> NOTE: This extension depends on the [yiisoft/yii2-bootstrap](https://github.com/yiisoft/yii2/tree/master/extensions/bootstrap) extension. Check the 
[composer.json](https://github.com/kartik-v/yii2-helpers/blob/master/composer.json) for this extension's requirements and dependencies. 
Note: Yii 2 framework is still in active development, and until a fully stable Yii2 release, your core yii2-bootstrap packages (and its dependencies) 
may be updated when you install or update this extension. You may need to lock your composer package versions for your specific app, and test 
for extension break if you do not wish to auto update dependencies.

### Html Class
[```VIEW DEMO```](http://demos.krajee.com/helper-functions/html)  

This class extends the [Yii Html Helper](https://github.com/yiisoft/yii2/blob/master/framework/helpers/Html.php) to incorporate additional HTML markup functionality and features available in [Bootstrap 3.0](http://getbootstrap.com/). The helper functions available in this class are:
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

This class extends the [Yii Inflector Helper](https://github.com/yiisoft/yii2/blob/master/framework/helpers/Inflector.php) with more utility functions for Yii developers. The helper functions available in this class are:
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

> Note: You must set the `minimum-stability` to `dev` in the **composer.json** file in your application root folder before installation of this extension.

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

**yii2-helpers** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
