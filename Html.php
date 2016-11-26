<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2016
 * @package yii2-helpers
 * @version 1.3.6
 */

namespace kartik\helpers;

use Closure;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html as YiiHtml;

/**
 * Html provides a set of static methods for generating commonly used HTML tags and extends [[YiiHtml]]
 * with additional bootstrap styled components and markup.
 *
 * Nearly all of the methods in this class allow setting additional html attributes for the html
 * tags they generate. You can specify for example. 'class', 'style'  or 'id' for an html element
 * using the `$options` parameter. See the documentation of the [[tag()]] method for more details.
 *
 * @see http://getbootstrap.com/css
 * @see http://getbootstrap.com/components
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class Html extends YiiHtml
{
    /**
     * Bootstrap float left CSS
     */
    const FLOAT_LEFT = 'pull-left';
    /**
     * Bootstrap float right CSS
     */
    const FLOAT_RIGHT = 'pull-right';
    /**
     * Bootstrap centered block CSS
     */
    const FLOAT_CENTER = 'center-block';
    /**
     * Bootstrap float left CSS for navigation bar
     */
    const NAVBAR_FLOAT_LEFT = 'navbar-left';
    /**
     * Bootstrap float right CSS for navigation bar
     */
    const NAVBAR_FLOAT_RIGHT = 'navbar-right';
    /**
     * Bootstrap clear floats CSS
     */
    const CLEAR_FLOAT = 'clearfix';
    /**
     * Bootstrap CSS for visible items
     */
    const SHOW = 'show';
    /**
     * Bootstrap CSS for hidden items
     */
    const HIDDEN = 'hidden';
    /**
     * Bootstrap CSS for invisible items
     */
    const INVISIBLE = 'invisible';
    /**
     * Bootstrap CSS for screen reader display
     */
    const SCREEN_READER = 'sr-only';
    /**
     * Bootstrap CSS for image replacer to hide text
     */
    const IMAGE_REPLACER = 'text-hide';
    /**
     * Bootstrap **extra small** size modifier
     */
    const SIZE_TINY = 'xs';
    /**
     * Bootstrap **small** size modifier
     */
    const SIZE_SMALL = 'sm';
    /**
     * Bootstrap **medium** size modifier (this is the default size)
     */
    const SIZE_MEDIUM = 'md';
    /**
     * Bootstrap **large** size modifier
     */
    const SIZE_LARGE = 'lg';
    /**
     * The **default** bootstrap contextual color type
     */
    const TYPE_DEFAULT = 'default';
    /**
     * The **primary** bootstrap contextual color type
     */
    const TYPE_PRIMARY = 'primary';
    /**
     * The **information** bootstrap contextual color type
     */
    const TYPE_INFO = 'info';
    /**
     * The **danger** bootstrap contextual color type
     */
    const TYPE_DANGER = 'danger';
    /**
     * The **warning** bootstrap contextual color type
     */
    const TYPE_WARNING = 'warning';
    /**
     * The **success** bootstrap contextual color type
     */
    const TYPE_SUCCESS = 'success';

    /**
     * Generates a bootstrap icon markup.
     *
     * Example:
     *
     * ~~~
     * echo Html::icon('pencil');
     * echo Html::icon('trash', ['style' => 'color: red; font-size: 2em']);
     * echo Html::icon('plus', ['class' => 'text-success']);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#glyphicons
     *
     * @param string $icon the bootstrap icon name without prefix (e.g. 'plus', 'pencil', 'trash')
     * @param array $options HTML attributes / options for the icon container
     * @param string $prefix the css class prefix - defaults to 'glyphicon glyphicon-'
     * @param string $tag the icon container tag (usually 'span' or 'i') - defaults to 'span'
     *
     * @return string
     */
    public static function icon($icon, $options = [], $prefix = 'glyphicon glyphicon-', $tag = 'span')
    {
        $class = isset($options['class']) ? ' ' . $options['class'] : '';
        $options['class'] = $prefix . $icon . $class;
        return static::tag($tag, '', $options);
    }

    /**
     * Generates a bootstrap label markup.
     *
     * Example:
     *
     * ~~~
     * echo Html::bsLabel('Draft');
     * echo Html::bsLabel('Inactive', 'danger');
     * echo Html::bsLabel('Active', 'success');
     * ~~~
     *
     * @see http://getbootstrap.com/components/#labels
     *
     * @param string $content the label content
     * @param string $type the bootstrap label type. Defaults to 'default'. Should be one of the bootstrap contextual
     * colors: 'default, 'primary', 'success', 'info', 'danger', 'warning'.
     * @param array $options HTML attributes / options for the label container
     * @param string $prefix the CSS class prefix. Defaults to 'label label-'.
     * @param string $tag the label container tag. Defaults to 'span'.
     *
     * @return string
     */
    public static function bsLabel($content, $type = 'default', $options = [], $prefix = 'label label-', $tag = 'span')
    {
        if (Enum::isEmpty($type)) {
            $type = self::TYPE_DEFAULT;
        }
        $class = isset($options['class']) ? ' ' . $options['class'] : '';
        $options['class'] = $prefix . $type . $class;
        return static::tag($tag, $content, $options);
    }

    /**
     * Generates a badge.
     *
     * Example:
     *
     * ~~~
     * echo Html::badge('10');
     * echo Html::badge('20', ['font-size' => '16px']);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#badges
     *
     * @param string $content the badge content
     * @param array $options HTML attributes / options for the label container
     * @param string $tag the label container tag. Defaults to 'span'.
     *
     * @return string
     */
    public static function badge($content, $options = [], $tag = 'span')
    {
        static::addCssClass($options, 'badge');
        return static::tag($tag, $content, $options);
    }

    /**
     * Generates a list group. Flexible and powerful component for displaying not only simple lists of elements, but
     * complex ones with custom content.
     *
     * Example:
     *
     * ~~~
     * echo Html::listGroup([
     *    [
     *      'content' => 'Cras justo odio',
     *      'url' => '#',
     *      'badge' => '14',
     *      'active' => true
     *    ],
     *    [
     *      'content' => 'Dapibus ac facilisis in',
     *      'url' => '#',
     *      'badge' => '2'
     *    ],
     *    [
     *      'content' => 'Morbi leo risus',
     *      'url' => '#',
     *      'badge' => '1'
     *    ],
     * ]);
     *
     * echo Html::listGroup([
     *    [
     *      'content' => ['heading' => 'Heading 1', 'body' => 'Cras justo odio'],
     *      'url' => '#',
     *      'badge' => '14',
     *      'active' => true
     *    ],
     *    [
     *      'content' => ['heading' => 'Heading 2', 'body' => 'Dapibus ac facilisis in'],
     *      'url' => '#',
     *      'badge' => '2'
     *    ],
     *    [
     *      'content' => ['heading' => 'Heading 2', 'body' => 'Morbi leo risus'],
     *      'url' => '#',
     *      'badge' => '1'
     *    ],
     * ]);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#list-group
     *
     * @param array $items the list group items. The following array key properties can be setup:
     * - `content`: mixed, the list item content. When passed as a string, it will display this directly as a raw
     *     content. When passed as an array, it requires these keys
     *     - `heading`: _string_, the content heading (optional).
     *     - `headingOptions`: _array_, the HTML attributes / options for heading container (optional).
     *     - `body`: _string_, the content body (optional).
     *     - `bodyOptions`: _array_, the HTML attributes / options for body container (optional).
     * - `url`: _string_|_array_, the url for linking the list item content (optional).
     * - `badge`: _string_, any badge content to be displayed for this list item (optional)
     * - `badgeOptions`: _array_, the HTML attributes / options for badge container (optional).
     * - `active`: _boolean_, to highlight the item as active (applicable only if $url is passed). Defaults to `false`.
     * - `options`: _array_, HTML attributes / options for the list group item container (optional).
     * @param array $options HTML attributes / options for the list group container
     * @param string $tag the list group container tag. Defaults to 'div'.
     * @param string $itemTag the list item container tag. Defaults to 'div'.
     *
     * @return string
     */
    public static function listGroup($items = [], $options = [], $tag = 'div', $itemTag = 'div')
    {
        static::addCssClass($options, 'list-group');
        $content = '';
        foreach ($items as $item) {
            $content .= static::getListGroupItem($item, $itemTag) . "\n";
        }
        return static::tag($tag, $content, $options);
    }

    /**
     * Generates a jumbotron - a lightweight, flexible component that can optionally extend the entire viewport to
     * showcase key content on your site.
     *
     * Example:
     *
     * ~~~
     * echo Html::jumbotron(
     *      '<h1>Hello, world</h1><p>This is a simple jumbotron-style component for calling extra attention to featured
     *     content or information.</p>'
     * );
     * echo Html::jumbotron([
     *     'heading' => 'Hello, world!',
     *     'body' => 'This is a simple jumbotron-style component for calling extra attention to featured content or information.'
     * ]);
     * echo Html::jumbotron([
     *     'heading' => 'Hello, world!',
     *     'body' => 'This is a simple jumbotron-style component for calling extra attention to featured content or information.'
     *     'buttons' => [
     *          [
     *              'label' => 'Learn More',
     *              'icon' => 'book',
     *              'url' => '#',
     *              'type' => Html::TYPE_PRIMARY,
     *              'size' => Html::LARGE
     *          ],
     *          [
     *              'label' => 'Contact Us',
     *              'icon' => 'phone',
     *              'url' => '#',
     *              'type' => Html::TYPE_DANGER,
     *              'size' => Html::LARGE
     *          ]
     *     ]
     * ]);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#jumbotron
     *
     * @param string|array $content the list item content. When passed as a string, it will display this directly as a raw
     * content. When passed as an array, it requires these keys:
     *
     * - `heading`: _string_, the jumbotron heading title
     * - `body`: _string_, the jumbotron content body
     * - `buttons`: _array_, the configuration for jumbotron buttons. The following properties can be set:
     *    - `label`: _string_, the button label
     *    - `icon`: _string_, the icon to place before the label
     *    - `url`: mixed, the button url
     *    - `type`: _string_, one of the bootstrap color modifier constants. Defaults to [[TYPE_DEFAULT]].
     *    - `size`: _string_, one of the size modifier constants
     *    - `options`: _array_, the HTML attributes / options for the button.
     * @param boolean $fullWidth whether this is a full width jumbotron without any corners. Defaults to `false`.
     * @param array $options HTML attributes / options for the jumbotron container.
     *
     * @return string
     */
    public static function jumbotron($content = [], $fullWidth = false, $options = [])
    {
        static::addCssClass($options, 'jumbotron');
        if (is_string($content)) {
            $html = $content;
        } else {
            $html = isset($content['heading']) ? "<h1>" . $content['heading'] . "</h1>\n" : '';
            $body = isset($content['body']) ? $content['body'] . "\n" : '';
            if (substr(preg_replace('/\s+/', '', $body), 0, 3) != '<p>') {
                $body = static::tag('p', $body);
            }
            $html .= $body;
            $buttons = '';
            if (isset($content['buttons'])) {
                foreach ($content['buttons'] as $btn) {
                    $label = (isset($btn['icon']) ? Html::icon(
                                $btn['icon']
                            ) . ' ' : '') . (isset($btn['label']) ? $btn['label'] : '');
                    $url = isset($btn['url']) ? $btn['url'] : '#';
                    $btnOptions = isset($btn['options']) ? $btn['options'] : [];
                    $class = 'btn' . (isset($btn['type']) ? ' btn-' . $btn['type'] : ' btn-' . self::TYPE_DEFAULT);
                    $class .= isset($btn['size']) ? ' btn-' . $btn['size'] : '';
                    static::addCssClass($btnOptions, $class);
                    $buttons .= Html::a($label, $url, $btnOptions) . " ";
                }
            }
            $html .= Html::tag('p', $buttons);
        }

        if ($fullWidth) {
            return static::tag('div', static::tag('div', $html, ['class' => 'container']), $options);
        } else {
            return static::tag('div', $html, $options);
        }
    }

    /**
     * Generates a panel for boxing content.
     *
     * Example:
     *
     * ~~~
     * echo Html::panel([
     *    'heading' => 'Panel Title',
     *    'body' => 'Panel Content',
     *    'footer' => 'Panel Footer',
     * ], Html::TYPE_PRIMARY);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#panels
     *
     * @param array $content the panel content configuration. The following properties can be setup:
     * - `preHeading`: _string_, raw content that will be placed before `heading` (optional).
     * - `heading`: _string_, the panel box heading (optional).
     * - `preBody`: _string_, raw content that will be placed before $body (optional).
     * - `body`: _string_, the panel body content - this will be wrapped in a "panel-body" container (optional).
     * - `postBody`: _string_, raw content that will be placed after $body (optional).
     * - `footer`: _string_, the panel box footer (optional).
     * - `postFooter`: _string_, raw content that will be placed after $footer (optional).
     * - `headingTitle`: _boolean_, whether to pre-style heading content with a '.panel-title' class. Defaults to `false`.
     * - `footerTitle`: _boolean_, whether to pre-style footer content with a '.panel-title' class. Defaults to `false`.
     * @param string $type the panel type which can be one of the bootstrap color modifier constants. Defaults to
     * [[TYPE_DEFAULT]].
     * - [[TYPE_DEFAULT]] or `default`
     * - [[TYPE_PRIMARY]] or `primary`
     * - [[TYPE_SUCCESS]] or `success`
     * - [[TYPE_INFO]] or `info`
     * - [[TYPE_WARNING]] or `warning`
     * - [[TYPE_DANGER]] or `danger`
     * @param array $options HTML attributes / options for the panel container
     * @param string $prefix the CSS prefix for panel type. Defaults to `panel panel-`.
     *
     * @return string
     */
    public static function panel($content = [], $type = 'default', $options = [], $prefix = 'panel panel-')
    {
        if (!is_array($content)) {
            return '';
        } else {
            static::addCssClass($options, $prefix . $type);
            $panel = static::getPanelContent($content, 'preHeading') .
                static::getPanelTitle($content, 'heading') .
                static::getPanelContent($content, 'preBody') .
                static::getPanelContent($content, 'body') .
                static::getPanelContent($content, 'postBody') .
                static::getPanelTitle($content, 'footer') .
                static::getPanelContent($content, 'postFooter');
            return static::tag('div', $panel, $options);
        }
    }

    /**
     * Generates a page header.
     *
     * Example:
     *
     * ~~~
     * echo Html::pageHeader(
     *    'Example page header',
     *    'Subtext for header'
     * );
     * ~~~
     *
     * @see http://getbootstrap.com/components/#page-header
     *
     * @param string $title the title to be shown
     * @param string $subTitle the subtitle to be shown as subtext within the title
     * @param array $options HTML attributes/ options for the page header
     *
     * @return string
     */
    public static function pageHeader($title, $subTitle = '', $options = [])
    {
        static::addCssClass($options, 'page-header');
        if (!Enum::isEmpty($subTitle)) {
            $title = "<h1>{$title} <small>{$subTitle}</small></h1>";
        } else {
            $title = "<h1>{$title}</h1>";
        }
        return static::tag('div', $title, $options);
    }

    /**
     * Generates a well container.
     *
     * @see http://getbootstrap.com/components/#wells
     *
     * @param string $content the content
     * @param string $size the well size. Should be one of the bootstrap size modifiers:
     * - [[SIZE_TINY]] or `xs`
     * - [[SIZE_SMALL]] or `sm`
     * - [[SIZE_MEDIUM]] or `md`
     * - [[SIZE_LARGE]] or `lg`
     * @param array $options HTML attributes / options for the well container.
     *
     * @return string
     */
    public static function well($content, $size = '', $options = [])
    {
        static::addCssClass($options, 'well');
        if (!Enum::isEmpty($size)) {
            static::addCssClass($options, 'well-' . $size);
        }
        return static::tag('div', $content, $options);
    }

    /**
     * Generates a bootstrap media object. Abstract object styles for building various types of components (like blog
     * comments, Tweets, etc) that feature a left-aligned or right-aligned  image alongside textual content.
     *
     * Example:
     *
     * ~~~
     * echo Html::media(
     *    'Media heading 1',
     *    'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.',
     *    '#',
     *    'http://placehold.it/64x64'
     * );
     * ~~~
     *
     * @see http://getbootstrap.com/components/#media
     *
     * @param string $heading the media heading.
     * @param string $body the media content.
     * @param string|array $src URL for the media article source.
     * @param string|array $img URL for the media image source.
     * @param array $srcOptions html options for the media article link.
     * @param array $imgOptions html options for the media image.
     * @param array $headingOptions HTML attributes / options for the media object heading container.
     * @param array $bodyOptions HTML attributes / options for the media object body container.
     * @param array $options HTML attributes / options for the media object container.
     * @param string $tag the media container tag. Defaults to 'div'.
     *
     * @return string
     */
    public static function media(
        $heading = '',
        $body = '',
        $src = '',
        $img = '',
        $srcOptions = [],
        $imgOptions = [],
        $headingOptions = [],
        $bodyOptions = [],
        $options = [],
        $tag = 'div'
    ) {
        static::addCssClass($options, 'media');
        if (!isset($srcOptions['class'])) {
            static::addCssClass($srcOptions, 'pull-left');
        }
        static::addCssClass($imgOptions, 'media-object');
        static::addCssClass($headingOptions, 'media-heading');
        static::addCssClass($bodyOptions, 'media-body');
        $source = static::a(static::img($img, $imgOptions), $src, $srcOptions);
        $heading = !Enum::isEmpty($heading) ? static::tag('h4', $heading, $headingOptions) : '';
        $content = !Enum::isEmpty($body) ? static::tag('div', $heading . "\n" . $body, $bodyOptions) : $heading;
        return static::tag($tag, $source . "\n" . $content, $options);
    }

    /**
     * Generates bootstrap list of media (useful for comment threads or articles lists).
     *
     * Example:
     *
     * ~~~
     * echo Html::mediaList([
     *   [
     *      'heading' => 'Media heading 1',
     *      'body' => 'Cras sit amet nibh libero, in gravida nulla. ' .
     *          'Nulla vel metus scelerisque ante sollicitudin commodo. '.
     *          'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *      'src' => '#',
     *      'img' => 'http://placehold.it/64x64',
     *      'items' => [
     *          [
     *              'heading' => 'Media heading 1.1',
     *              'body' => 'Cras sit amet nibh libero, in gravida nulla. ' .
     *                  'Nulla vel metus scelerisque ante sollicitudin commodo. ' .
     *                  'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *              'src' => '#',
     *              'img' => 'http://placehold.it/64x64'
     *          ],
     *          [
     *              'heading' => 'Media heading 1.2',
     *              'body' => 'Cras sit amet nibh libero, in gravida nulla. ' .
     *                  'Nulla vel metus scelerisque ante sollicitudin commodo. ' .
     *                  'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *              'src' => '#',
     *              'img' => 'http://placehold.it/64x64'
     *          ],
     *      ]
     *   ],
     *   [
     *      'heading' => 'Media heading 2',
     *      'body' => 'Cras sit amet nibh libero, in gravida nulla. ' .
     *          'Nulla vel metus scelerisque ante sollicitudin commodo. ' .
     *          'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *      'src' => '#',
     *      'img' => 'http://placehold.it/64x64'
     *   ],
     * ]);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#media
     *
     * @param array $items the configuration of media items. The following properties can be set as array keys:
     *  - `items`: _array_, the sub media items (similar in configuration to items) (optional)
     *  - `heading`: _string_, the media heading
     *  - `body`: _string_, the media content
     *  - `src`: mixed, URL for the media article source
     *  - `img`: mixed, URL for the media image source
     *  - `srcOptions`: _array_, HTML attributes / options for the media article link (optional)
     *  - `imgOptions`: _array_, HTML attributes / options for the media image (optional)
     *  - `headingOptions`: _array_, HTML attributes / options for the media heading (optional)
     *  - `bodyOptions`: _array_, HTML attributes / options for the media body (optional)
     *  - `options`: _array_, HTML attributes / options for each media item (optional)
     * @param array $options HTML attributes / options for the media list container
     *
     * @return string
     */
    public static function mediaList($items = [], $options = [])
    {
        static::addCssClass($options, 'media-list');
        $content = static::getMediaList($items);
        return static::tag('ul', $content, $options);
    }

    /**
     * Generates a generic bootstrap close icon button for dismissing content like modals and alerts.
     *
     * Example:
     *
     * ~~~
     * echo Html::closeButton();
     * echo Html::closeButton(Html::icon('remove-sign');
     * ~~~
     *
     * @see http://getbootstrap.com/css/#helper-classes-close
     *
     * @param string $label the close icon label. Defaults to `&times;`.
     * @param array $options HTML attributes / options for the close icon button.
     * @param string $tag the HTML tag for rendering the close icon. Defaults to `button`.
     *
     * @return string
     */
    public static function closeButton($label = '&times;', $options = [], $tag = 'button')
    {
        static::addCssClass($options, 'close');
        if ($tag == 'button') {
            $options['type'] = 'button';
        }
        $options['aria-hidden'] = 'true';
        return static::tag($tag, $label, $options);
    }

    /**
     * Generates a bootstrap caret.
     *
     * Example:
     *
     * ~~~
     * echo 'Down Caret ' . Html::caret();
     * echo 'Up Caret ' . Html::caret('up');
     * echo 'Disabled Caret ' . Html::caret('down', true);
     * ~~~
     *
     * @see http://getbootstrap.com/css/#helper-classes-carets
     *
     * @param string $direction whether to display as 'up' or 'down' direction. Defaults to `down`.
     * @param boolean $disabled if the caret is to be displayed as disabled. Defaults to `false`.
     * @param array $options html options for the caret container.
     * @param string $tag the html tag for rendering the caret. Defaults to `span`.
     *
     * @return string
     */
    public static function caret($direction = 'down', $disabled = false, $options = [], $tag = 'span')
    {
        static::addCssClass($options, 'caret');

        if (!isset($options['style'])) {
            $options['style'] = 'margin-bottom: 3px;';
        }

        if ($disabled) {
            $options['style'] = $options['style'] . ';  border-top-color: #bbb; border-bottom-color: #bbb;';
        }

        if ($direction == 'up') {
            return static::tag($tag, static::tag($tag, '', $options), ['class' => 'dropup']);
        } else {
            return static::tag($tag, '', $options);
        }
    }

    /**
     * Generates a bootstrap abbreviation.
     *
     * Example:
     *
     * ~~~
     * echo Html::abbr('HTML', 'HyperText Markup Language')  . ' is the best thing since sliced bread';
     * echo Html::abbr('HTML', 'HyperText Markup Language', true);
     * ~~~
     *
     * @see http://getbootstrap.com/css/#type-abbreviations
     *
     * @param string $content the abbreviation content
     * @param string $title the abbreviation title
     * @param boolean $initialism if set to true, will display a slightly smaller font-size.
     * @param array $options html options for the abbreviation
     *
     * @return string
     */
    public static function abbr($content, $title, $initialism = false, $options = [])
    {
        $options['title'] = $title;
        if ($initialism) {
            static::addCssClass($options, 'initialism');
        }
        return static::tag('abbr', $content, $options);
    }

    /**
     * Generates a bootstrap blockquote.
     *
     * Example:
     *
     * ~~~
     * echo Html::blockquote(
     *      'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.',
     *      'Someone famous in {source}',
     *      'International Premier League',
     *      'IPL'
     * );
     * ~~~
     *
     * @see http://getbootstrap.com/css/#type-blockquotes
     *
     * @param string $content the blockquote content
     * @param string $citeContent the content of the citation (optional) - this should typically include the tag
     * '{source}' to embed the cite source
     * @param string $citeTitle the cite source title (optional)
     * @param string $citeSource the cite source (optional)
     * @param array $options html options for the blockquote
     *
     * @return string
     */
    public static function blockquote($content, $citeContent = '', $citeTitle = '', $citeSource = '', $options = [])
    {
        $content = static::tag('p', $content);
        if (!Enum::isEmpty($citeContent)) {
            $source = static::tag('cite', $citeSource, ['title' => $citeTitle]);
            $content .= "\n<small>" . str_replace('{source}', $source, $citeContent) . "</small>";
        }
        return static::tag('blockquote', $content, $options);
    }

    /**
     * Generates a bootstrap address block.
     *
     * Example:
     *
     * ~~~
     * echo Html::address(
     *      'Twitter, Inc.',
     *      ['795 Folsom Ave, Suite 600', 'San Francisco, CA 94107'],
     *      ['Res' => '(123) 456-7890', 'Off'=> '(456) 789-0123'],
     *      ['Res' => 'first.last@example.com', 'Off' => 'last.first@example.com']
     * );
     * $address = Html::address(
     *      'Twitter, Inc.',
     *      ['795 Folsom Ave, Suite 600', 'San Francisco, CA 94107'],
     *      ['Res' => '(123) 456-7890', 'Off'=> '(456) 789-0123'],
     *      ['Res' => 'first.last@example.com', 'Off' => 'last.first@example.com'],
     *      Html::icon('phone'),
     *      Html::icon('envelope')
     * );
     * echo Html::well($address, Html::SIZE_TINY);
     * ~~~
     *
     * @see http://getbootstrap.com/css/#type-addresses
     *
     * @param string $name the addressee name.
     * @param array $lines the lines of address information.
     * @param array $phone the list of phone numbers - passed as $key => $value, where:
     *    - `$key`: _string_, is the phone type could be 'Res', 'Off', 'Cell', 'Fax'.
     *    - `$value`: _string_, is the phone number.
     * @param array $email the list of email addresses - passed as $key => $value, where:
     *    - `$key`: _string_, is the email type could be 'Res', 'Off'.
     *    - `$value`: _string_, is the email address.
     * @param array $options html options for the address.
     * @param string $phoneLabel the prefix label for each phone - defaults to '(P)'.
     * @param string $emailLabel the prefix label for each email - defaults to '(E)'.
     *
     * @return string
     */
    public static function address(
        $name,
        $lines = [],
        $phone = [],
        $email = [],
        $options = [],
        $phoneLabel = '(P)',
        $emailLabel = '(E)'
    ) {
        Enum::initI18N();
        $addresses = '';
        if (!Enum::isEmpty($lines)) {
            $addresses = implode('<br>', $lines) . "<br>\n";
        }

        $phones = '';
        foreach ($phone as $type => $number) {
            if (is_numeric($type)) { // no keys were passed to the phone array
                $type = static::tag('abbr', $phoneLabel, ['title' => Yii::t('kvenum', 'Phone')]) . ': ';
            } else {
                $type = static::tag('abbr', $phoneLabel . ' ' . $type, ['title' => Yii::t('kvenum', 'Phone')]) . ': ';
            }
            $phones .= "{$type}{$number}<br>\n";
        }

        $emails = '';
        foreach ($email as $type => $addr) {
            if (is_numeric($type)) { // no keys were passed to the email array
                $type = Html::tag('abbr', $emailLabel, ['title' => Yii::t('kvenum', 'Email')]) . ': ';
            } else {
                $type = Html::tag('abbr', $emailLabel . ' ' . $type, ['title' => Yii::t('kvenum', 'Email')]) . ': ';
            }
            $emails .= $type . static::mailto($addr, $addr) . "<br>\n";
        }
        return static::tag('address', "<strong>{$name}</strong><br>\n" . $addresses . $phones . $emails, $options);
    }

    /**
     * Generates a bootstrap toggle button group (checkbox or radio type)
     *
     * @see http://getbootstrap.com/javascript/#buttons-checkbox-radio
     *
     * @param string $type whether checkbox or radio.
     * @param string $name the name attribute of each checkbox.
     * @param string|array $selection the selected value(s).
     * @param array $items the data item used to generate the checkboxes/radios. The array keys are the
     * checkbox/radio values, while the array values are the corresponding labels.
     * @param array $options options (name => config) for the checkbox/radio list container tag. The following
     * options are specially handled:
     *
     * - `tag`: _string_, the tag name of the container element.
     * - `unselect`: _string_, the value that should be submitted when none of the checkboxes/radios is selected. By
     *   setting this option, a hidden input will be generated.
     * - `encode`: boolean, whether to HTML-encode the checkbox/radio labels. Defaults to `true`. This option is ignored
     *   if `item` option is set.
     * - `separator`: _string_, the HTML code that separates items.
     * - `itemOptions`: _array_, the options for generating the checkbox/radio tag using [[checkbox()]]/[[radio()]].
     * - `item`: _Closure_, a callback that can be used to customize the generation of the HTML code corresponding to a
     *   single item in $items. The signature of this callback must be:
     *
     *   ~~~
     *   function ($index, $label, $name, $checked, $value)
     *   ~~~
     *
     *   where `$index` is the zero-based index of the checkbox/radio in the whole list; `$label` is the label for the
     *   checkbox/radio; and `$name`, `$value` and `$checked` represent the name, value and the checked status of the
     *   checkbox/radio input, respectively.
     *
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     *
     * @return string the generated toggle button group
     */
    public static function getButtonGroup($type, $name, $selection = null, $items = [], $options = [])
    {
        $class = $type . 'List';
        static::addCssClass($options, 'btn-group');
        $options['data-toggle'] = 'buttons';
        $options['inline'] = true;
        if (!isset($options['itemOptions']['labelOptions']['class'])) {
            $options['itemOptions']['labelOptions']['class'] = 'btn btn-default';
        }
        if (!isset($options['item']) || !$options['item'] instanceof Closure) {
            /** @noinspection PhpUnusedParameterInspection */
            /**
             * @param string $index
             * @param string $label
             * @param string $name
             * @param boolean $checked
             * @param string $value
             */
            $options['item'] = function ($index, $label, $name, $checked, $value) use ($type, $options) {
                $opts = isset($options['itemOptions']) ? $options['itemOptions'] : [];
                $encode = !isset($options['encode']) || $options['encode'];
                if (!isset($opts['labelOptions'])) {
                    $opts['labelOptions'] = ArrayHelper::getValue($options, 'itemOptions.labelOptions', []);
                }
                if ($checked) {
                    Html::addCssClass($opts['labelOptions'], 'active');
                }
                return static::$type(
                    $name,
                    $checked,
                    array_merge(
                        $opts, ['value' => $value, 'label' => $encode ? static::encode($label) : $label]
                    )
                );
            };
        }
        return static::$class($name, $selection, $items, $options);
    }

    /**
     * Generates a bootstrap checkbox button group. A checkbox button group allows multiple selection,
     * like [[listBox()]]. As a result, the corresponding submitted value is an array.
     *
     * Example:
     *
     * ~~~
     * echo Html::checkboxButtonGroup('cbx', [1, 2], [1 => 'Check 1', 2 => 'Check 2', 3 => 'Check 3']);
     * ~~~
     *
     * @see http://getbootstrap.com/javascript/#buttons-checkbox-radio
     *
     * @param string $name the name attribute of each checkbox.
     * @param string|array $selection the selected value(s).
     * @param array $items the data item used to generate the checkboxes. The array keys are the checkbox values,
     * while the array values are the corresponding labels.
     * @param array $options options (name => config) for the checkbox list container tag. The following options are
     * specially handled:
     *
     * - `tag`: _string_, the tag name of the container element.
     * - `unselect`: _string_, the value that should be submitted when none of the checkboxes is selected. You may set this
     *    option to be null to prevent default value submission. If this option is not set, an empty string will be
     *    submitted.
     * - `encode`: _boolean_, whether to HTML-encode the checkbox labels. Defaults to `true`. This option is ignored if
     *     `item` option is set.
     * - `separator`: _string_, the HTML code that separates items.
     * - `itemOptions`: _array_, the options for generating the checkbox tag using [[checkbox()]].
     * - `item`: _Closure_, a callback that can be used to customize the generation of the HTML code corresponding to a
     *     single item in $items. The signature of this callback must be:
     *
     *   ~~~
     *   function ($index, $label, $name, $checked, $value)
     *   ~~~
     *
     *   where `$index` is the zero-based index of the checkbox in the whole list; `$label` is the label for the checkbox;
     *   and `$name`, `$value` and `$checked` represent the name, value and the checked status of the checkbox input.
     *
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     *
     * @return string the generated checkbox button group
     */
    public static function checkboxButtonGroup($name, $selection = null, $items = [], $options = [])
    {
        return static::getButtonGroup('checkbox', $name, $selection, $items, $options);
    }

    /**
     * Generates a bootstrap radio button group. A radio button group is like a checkbox button group, except that it
     * only allows single selection.
     *
     * Example:
     *
     * ~~~
     * echo Html::radioButtonGroup('rdo', [1], [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']);
     * ~~~
     *
     * @see http://getbootstrap.com/javascript/#buttons-checkbox-radio
     *
     * @param string $name the name attribute of each radio.
     * @param string|array $selection the selected value(s).
     * @param array $items the data item used to generate the radios. The array keys are the radio values, while the
     * array values are the corresponding labels.
     * @param array $options options (name => config) for the radio list container tag. The following options are
     * specially handled:
     *
     * - `tag`: _string_, the tag name of the container element.
     * - `unselect`: _string_, the value that should be submitted when none of the radio buttons is selected. By
     *   setting this option, a hidden input will be generated.
     * - `encode`: boolean, whether to HTML-encode the radio button labels. Defaults to `true`. This option is ignored
     *   if `item` option is set.
     * - `separator`: _string_, the HTML code that separates items.
     * - `itemOptions`: _array_, the options for generating the radio button tag using [[radio()]].
     * - `item`: _Closure_, a callback that can be used to customize the generation of the HTML code corresponding to a
     *   single item in $items. The signature of this callback must be:
     *
     *   ~~~
     *   function ($index, $label, $name, $checked, $value)
     *   ~~~
     *
     *   where `$index` is the zero-based index of the radio button in the whole list; `$label` is the label for the radio
     *   button; and `$name`, `$value` and `$checked` represent the name, value and the checked status of the radio button
     *   input.
     *
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     *
     * @return string the generated radio button group
     */
    public static function radioButtonGroup($name, $selection = null, $items = [], $options = [])
    {
        return static::getButtonGroup('radio', $name, $selection, $items, $options);
    }

    /**
     * Generates an active bootstrap checkbox button group. A checkbox button group allows multiple selection, like
     * [[listBox()]]. As a result, the corresponding submitted value is an array. The selection of the checkbox button
     * group is taken from the value of the model attribute.
     *
     * Example:
     *
     * ~~~
     * echo Html::activeCheckboxButtonGroup($model, 'attr', [1 => 'Check 1', 2 => 'Check 2', 3 => 'Check 3']);
     * ~~~
     *
     * @see http://getbootstrap.com/javascript/#buttons-checkbox-radio
     *
     * @param Model $model the model object
     * @param string $attribute the attribute name or expression. See [[getAttributeName()]] for the format about
     * attribute expression.
     * @param array $items the data item used to generate the checkboxes. The array keys are the checkbox values, and
     * the array values are the corresponding labels. Note that the labels will NOT be HTML-encoded, while the
     * values will.
     * @param array $options options (name => config) for the checkbox list container tag. The following options are
     * specially handled:
     *
     * - `tag`: _string_, the tag name of the container element.
     * - `unselect`: _string_, the value that should be submitted when none of the checkboxes is selected. You may set this
     *    option to be null to prevent default value submission. If this option is not set, an empty string will be
     *    submitted.
     * - `encode`: _boolean_, whether to HTML-encode the checkbox labels. Defaults to `true`. This option is ignored if
     *     `item` option is set.
     * - `separator`: _string_, the HTML code that separates items.
     * - `itemOptions`: _array_, the options for generating the checkbox tag using [[checkbox()]].
     * - `item`: _Closure_, a callback that can be used to customize the generation of the HTML code corresponding to a
     *     single item in $items. The signature of this callback must be:
     *
     *   ~~~
     *   function ($index, $label, $name, $checked, $value)
     *   ~~~
     *
     *   where `$index` is the zero-based index of the checkbox in the whole list; `$label` is the label for the checkbox;
     *   and `$name`, `$value` and `$checked` represent the name, value and the checked status of the checkbox input.
     *
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     *
     * @return string the generated bootstrap checkbox button group
     */
    public static function activeCheckboxButtonGroup($model, $attribute, $items, $options = [])
    {
        return static::activeListInput('checkboxButtonGroup', $model, $attribute, $items, $options);
    }

    /**
     * Generates an active bootstrap radio button group. A radio button group is like a checkbox button group, except
     * that it only allows single selection. The selection of the radio buttons is taken from the value of the model
     * attribute.
     *
     * Example:
     *
     * ~~~
     * echo Html::activeRadioButtonGroup($model, 'attr', [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3']);
     * ~~~
     *
     * @see http://getbootstrap.com/javascript/#buttons-checkbox-radio
     *
     * @param Model $model the model object
     * @param string $attribute the attribute name or expression. See [[getAttributeName()]] for the format about
     * attribute expression.
     * @param array $items the data item used to generate the radio buttons. The array keys are the radio values, and
     * the array values are the corresponding labels. Note that the labels will NOT be HTML-encoded, while the
     * values will.
     * @param array $options options (name => config) for the radio button list container tag.
     * The following options are specially handled:
     *
     * - `tag`: _string_, the tag name of the container element.
     * - `unselect`: _string_, the value that should be submitted when none of the radio buttons is selected. By
     *   setting this option, a hidden input will be generated.
     * - `encode`: boolean, whether to HTML-encode the radio button labels. Defaults to `true`. This option is ignored
     *   if `item` option is set.
     * - `separator`: _string_, the HTML code that separates items.
     * - `itemOptions`: _array_, the options for generating the radio button tag using [[radio()]].
     * - `item`: _Closure_, a callback that can be used to customize the generation of the HTML code corresponding to a
     *   single item in $items. The signature of this callback must be:
     *
     *   ~~~
     *   function ($index, $label, $name, $checked, $value)
     *   ~~~
     *
     *   where `$index` is the zero-based index of the radio button in the whole list; `$label` is the label for the radio
     *   button; and `$name`, `$value` and `$checked` represent the name, value and the checked status of the radio button
     *   input.
     *
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     *
     * @return string the generated bootstrap radio button group
     */
    public static function activeRadioButtonGroup($model, $attribute, $items, $options = [])
    {
        return static::activeListInput('radioButtonGroup', $model, $attribute, $items, $options);
    }

    /**
     * Processes and generates each list group item
     *
     * @param array $item the list item configuration.  The following array key properties can be setup:
     * - `content`: _string_|_array_, the list item content. When passed as a string, it will display this directly
     *   as a raw content. When passed as an array, it requires these keys:
     *      - `heading`: _string_, the content heading (optional).
     *         - `body`: _string_, the content body (optional).
     *         - `headingOptions`: _array_, the HTML attributes / options for heading container (optional).
     *         - `bodyOptions`: _array_, the HTML attributes / options for body container (optional).
     * - `url`: string|array, the url for linking the list item content (optional).
     * - `badge`: _string_, any badge content to be displayed for this list item (optional)
     * - `badgeOptions`: _array_, the HTML attributes / options for badge container (optional).
     * - `active`: _boolean_, to highlight the item as active (applicable only if $url is passed). Defaults to `false`.
     * - `options`: _array_, HTML attributes / options for the list group item container (optional).
     * @param string $tag the list item container tag (applied if it is not a link)
     *
     * @return string
     */
    protected static function getListGroupItem($item, $tag)
    {
        static::addCssClass($item['options'], 'list-group-item');
        $heading = $body = $badge = $content = $url = $active = '';
        $options = $headingOptions = $bodyOptions = $badgeOptions = [];
        extract($item);
        if (is_array($content)) {
            extract($content);
            if (!Enum::isEmpty($heading)) {
                static::addCssClass($headingOptions, 'list-group-item-heading');
                $heading = static::tag('h4', $heading, $headingOptions);
            }
            if (!Enum::isEmpty($body)) {
                static::addCssClass($bodyOptions, 'list-group-item-text');
                $body = static::tag('p', $body, $bodyOptions);
            }
            $content = $heading . "\n" . $body;
        }
        if (!Enum::isEmpty($badge)) {
            $content = static::badge($badge, $badgeOptions) . $content;
        }
        if (!Enum::isEmpty($url)) {
            if ($active) {
                static::addCssClass($options, 'active');
            }
            return static::a($content, $url, $options);
        } else {
            return static::tag($tag, $content, $options);
        }
    }

    /**
     * Generates panel content
     *
     * @param array $content the panel content components.
     * @param string $type one of the content settings
     *
     * @return string
     */
    protected static function getPanelContent($content, $type)
    {
        $out = ArrayHelper::getValue($content, $type, '');
        return !Enum::isEmpty($out) ? $out . "\n" : '';
    }

    /**
     * Generates panel title for heading and footer.
     *
     * @param array $content the panel content settings.
     * @param string $type whether `heading` or `footer`
     *
     * @return string
     */
    protected static function getPanelTitle($content, $type)
    {
        $title = ArrayHelper::getValue($content, $type, '');
        if (!Enum::isEmpty($title)) {
            if (ArrayHelper::getValue($content, "{$type}Title", true) === true) {
                $title = static::tag("h3", $title, ["class" => "panel-title"]);
            }
            return static::tag("div", $title, ["class" => "panel-{$type}"]) . "\n";
        } else {
            return '';
        }
    }

    /**
     * Processes media items array to generate a recursive list.
     *
     * @param array $items the media items
     * @param boolean $top whether item is the topmost parent
     *
     * @return string
     */
    protected static function getMediaList($items, $top = true)
    {
        $content = '';
        foreach ($items as $item) {
            $tag = $top ? 'li' : 'div';
            if (isset($item['items'])) {
                $item['body'] .= static::getMediaList($item['items'], false);
            }
            $content .= static::getMediaItem($item, $tag) . "\n";
        }
        return $content;
    }

    /**
     * Processes and generates each media item
     *
     * @param array $item the media item configuration
     * @param string $tag the media item container tag
     *
     * @return string
     */
    protected static function getMediaItem($item = [], $tag = 'div')
    {
        $heading = $body = $img = '';
        $src = '#';
        $srcOptions = $imgOptions = $options = $headingOptions = $bodyOptions = [];
        extract($item);
        return static::media(
            $heading,
            $body,
            $src,
            $img,
            $srcOptions,
            $imgOptions,
            $headingOptions,
            $bodyOptions,
            $options,
            $tag
        );
    }
}
