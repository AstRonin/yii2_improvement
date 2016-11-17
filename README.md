# yii2 improvements

My small improvements about yii2

---

##Please, attention!
### These improvements use for YII2 Beta! @See https://github.com/kartik-v/yii2-widgets

---

common\components\Controller, common\components\UrlManager
------------------------

Set the application language if provided by GET, session or cookie.

Rewrite from:

* [Yii 1.1: SEO-conform Multilingual URLs + Language Selector Widget (i18n)](http://www.yiiframework.com/wiki/294/seo-conform-multilingual-urls-language-selector-widget-i18n/)

common\components\Pjax
------------------------

Added **'basePoint'**

We should set new **$basePoint** instead of **'document'** for jQuery selectors
when we use Modal of Bootstrap with remote data.

For example **'.modal-dialog'**

Don't forget to write:
```javascript
$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
})
```
for clean remote data

common\components\grid\SerialColumn
------------------------

Added only **$secondRow**. We use this if we need two rows in a header

common\components\grid\GridView
------------------------

Methods for

* header and body separately
* add additional (probably hidden) row
* several attributes into one column

common\components\grid\DataColumn
------------------------

* added $absent if we don't need data columns
* if we have list of attribute in a header [@see GridView]

common\components\data\Sort
------------------------
Added **$defaultSortAttribute**. We can set default Sorting for all attributes

# Usage

### Several attributes are into one column:
```php
'attributes' => ['attribute1', 'attribute2'],
'labels' => ['Label1', 'Label2'],
```

### Two rows are in a header:
Set in *column* for first level
```php
'headerOptions' => [
    'colspan' => '2',
],
```
Set in *column* for second level:
```php
'headerOptions' => [
    'secondRow' => true,
],
```
For other *columns*:
```php
'headerOptions' => [
    'rowspan' => '2',
],
```
Don't forget set for column if needed:
```php
'class' => 'common\components\grid\SerialColumn',
```

### Header and body separately:
```php
'fixedHeader' => true,
'fixedHeaderTemplate' => '<div><div>{header}</div></div><div>{body}</div>',
```

### If we have use additional (probably hidden) row:
```php
'afterRow' => ['\common\components\grid\GridView', 'renderTableOtherRow'],
'otherRowOptions' => [
   'style' => 'display: none',
],
```
And then additional rows after **'columns' => [...]**
```php
'otherRowColumns' => [...]
```
