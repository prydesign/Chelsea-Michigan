=========================
jWYSIWYG 0.96 User Manual
=========================

Copyright (c) 2009-2010 Juan M Martínez
Dual licensed under the `MIT
<http://github.com/akzhan/jwysiwyg/raw/master/MIT-LICENSE.txt>`_ and `GPL
<http://github.com/akzhan/jwysiwyg/raw/master/GPL-LICENSE.txt>`_ licenses.

.. contents::

========
Requires
========

jQuery 1.3.2 or higher (tested with jQuery 1.5.1).

========
Supports
========

`jQuery UI Dialog
<http://jqueryui.com/demos/dialog/>`_ and `SimpleModal
<http://www.ericmmartin.com/projects/simplemodal/>`_ for insertTable and insertImage buttons.

`jQuery UI Resizable
<http://jqueryui.com/demos/resizable/>`_ for resize of editor.

===========
Tested with
===========

Tested in Safari 4, Firefox 3.5, Chrome 4.0, Internet Explorer 8.

.. note::

    In IE transparent GIF may have an `issue <https://github.com/akzhan/jwysiwyg/issues#issue/28>`_
    to resolve it:

    * open jquery.wysiwyg.css
    * replace string *jquery.wysiwyg.gif* with *jquery.wysiwyg.no-alpha.gif*

Some minor bugs still exist while 1.0 not reached.

========
Web site
========

`jWYSIWYG on GitHub <http://github.com/akzhan/jwysiwyg>`_

===========
Quick Start
===========

The following code creates a jWYSIWYG editor with the default options::

    <script type="text/javascript" src="jquery.wysiwyg.js"></script>
    <script type="text/javascript">
    $(function() {
        $('#wysiwyg').wysiwyg();
    });
    </script>

    <textarea id="wysiwyg"></textarea>


Activating Hidden Controls
--------------------------

Toolbar buttons are selected with the ``controls`` configuration option::

    $('#wysiwyg').wysiwyg({
        controls: {
            strikeThrough: { visibile: true },
            underline: { visible: true },
            subscript: { visible: true },
            superscript: { visible: true }
        }
    });

A full list of available ``controls`` options is available in ____.


Adding Custom Controls
----------------------

Custom controls can also be specified with the ``controls`` option::

    $('#wysiwyg').wysiwyg({
        controls: {
            alertSep: { separator: true },
            alert: {
                visible: true,
                exec: function() { alert('Hello World'); },
                className: 'alert'
            }
        }
    })

Another way::

    $('#wysiwyg').wysiwyg("addControl",
        "controlName",
        {
            icon: "/path/to/icon.png",
            exec:  function() { alert('Hello World'); }
        }
    );


Styling the Content Inside the Editor
-------------------------------------

To apply a CSS stylesheet to the content inside the editor, use the ``css``
configuration option::

    $('#wysiwyg').wysiwyg({
        css: 'editor.css'
    });

The editor will not inherit the style of the containing page anyway, you must
specify a CSS file to apply to it.


Clear the Editor
----------------

To clear the editor at any time::

    $('#wysiwyg').wysiwyg('clear');


Insert an Image
---------------

When the #insertImage link is clicked, insert an image inline at the current
cursor location in the editor::

    $('a[href="#insertImage"]').click(function() {
        $('#wysiwyg').wysiwyg('insertImage', 'img/hourglass.gif');
    });

.. note::

    Include file wysiwyg.image.js to provide this function

Insert an Image with Attributes
-------------------------------

Add some additional attributes to the image, as well::

    $('a[href="#insertImage"]').click(function() {
        $('#wysiwyg').wysiwyg('insertImage', 'img/hourglass.gif', { 'class': 'myClass', 'className': 'myClass' });
    });

Note that the class attribute is added twice, because the ``class`` DOM
attribute is recognized on IE but not on Firefox, and the ``className``
attribute is recognized on Firefox but not on IE.

.. note::

    Include file wysiwyg.image.js to provide this function

======================
Advanced Customization
======================

Available Configuration Options
-------------------------------

Additional configuration options are specified by passing a javascript object to
the wysiwyg() function when it is first called on a textarea. Available keys are:

``html``
    A string containing the source HTML code used inside the editor's iframe.
    This is a template where ``INITIAL_CONTENT`` later replaced by the
    appropriate code for the editor instance, so this string must be present in
    this option.

``debug``
    A boolean, enabling or disabling debugging.

``css``
    A string containing the path to a CSS file which will be included in the
    editor's iframe.

``autoGrow``
    A boolean.

``autoSave``
    A boolean. If ``true``, the editor will copy its contents back to the
    original textarea anytime it is updated. If ``false``, this must be done
    manually.

``brIE``
    A boolean. If ``true``, a ``<br/>`` will be inserted for a newline in IE.

``formHeight``
    An integer. Height of dialog form.

``formWidth``
    An integer. Width of dialog form.

``i18n``
    A bool or string. If ``false`` then no internationalization, otherwise set
    to language (ex. ``ru``)

``iFrameClass``
    A string, that specify ``class`` attribute of iframe element

``initialContent``
    A string. Default ``<p>Initial Content</p>``

``maxHeight``
    An integer. autoGrow max height

``messages``
    A javascript object with key, value pairs setting custom messages for
    certain conditions. Available keys are:
    
    * ``nonSelection``: Message to display when the Create Link button is
      pressed with no text selected.

``toolbarHtml``
    A string containing the source HTML code

``resizeOptions``
    A boolean. Depends on **jquery.ui.resizable**. If ``false`` the editor will
    not be resizeable.

``removeHeadings``
    A boolean. If ``true``, the editor will remove also headings when remove format 
    is used. Otherwise headings will not be removed. Default is ``false``.

``rmUnusedControls``
    A boolean. If ``true``, the editor will remove all controls which are not
    mentioned in ``controls`` option.
    In this example only bold control will be available in toolbar::
    
        $("textarea").wysiwyg({
            rmUnusedControls: true,
            controls: {
                bold: { visible : true },
            }
        });
    
    See also `help/examples/10-custom-controls.html
    <https://github.com/akzhan/jwysiwyg/blob/master/help/examples/10-custom-controls.html>`_

``rmUnwantedBr``
    A boolean. If ``true``, the editor will not add extraneous ``<br/>`` tags.

``tableFiller``
    A string. Default ``Lorem ipsum``

``events``
    A javascript object specifying events. Events are specified as ``key: value``
    pairs in the javascript object,
    where the key is the name of the event and the value is javascript function::

        {
            click: function(event) {
                if ($("#click-inform:checked").length > 0) {
                    event.preventDefault();
                    alert("You have clicked jWysiwyg content!");
                }
            }
        }

``controls``
    A javascript object specifying control buttons and separators to include in
    the toolbar. This can consist of built-in controls and custom controls.
    Controls are specified as key, value pairs in the javascript object, where
    the key is the name of the control and the value is another javascript
    object with a specific signature.
    
    The signature of a control object looks like this::
    
        {
            // If true, this object will just be a vertical separator bar,
            // and no other keys should be set.
            separator: { true | false },
            
            // If false, this button will be hidden.
            visible: { true | false },
            
            // In toolbar there are groups of controls. At the end of each group
            // is placed an auto separator.
            // Set which group to assign or create a new group with unique number.
            groupIndex: { number },
            
            // Tags are used to hilight control when current selection
            // is wrapped by one of these tags.
            tags: ['b', 'strong'],
            
            // CSS classes are used to hilight control when current selection
            // has chosen css classes.
            css: {
                textAlign: 'left',
                fontStyle: 'italic',
                ...
            },
            
            // Function to execute when this command is triggered. If this
            // key is provided, CSS classes/tags will not be applied, and
            // any built-in functionality will not be triggered.
            exec: function() { ... },
            
            // Hotkeys binds on keydown event
            hotkey: {
            	"alt":   1 | 0,
            	"ctrl":  1 | 0,
            	"shift": 1 | 0,
            	"key":   { event.keyCode }
            },
            
            // Tooltip
            tooltip: { string },
            
            // Path to icon
            icon: { string },
            
            // Automatically set when custom control is used
            custom: { true | false }
        }
    
    If you wish to override the default behavior of built-in controls, you can
    do so by specifying only the keys which you wish to change the behavior of.
    For example, since the ``strikeThrough`` control is not visibly by default,
    to enable it we only have to specify::
    
        strikeThrough: { visible: true }
    
    Additionally, custom controls may be specified by adding new keys with the
    same signature as a control object. For example, if we wish to create a
    ``quote`` control which creates ``<blockquote>`` tags, we could do specify
    this key::
    
        quote: { visible; true, tags: ['blockquote'], css: { class: 'quote', className: 'quote' } }
    
    Note that when defining custom controls, you will most likely want to add
    additional CSS to style the resulting toolbar button. The CSS to style a
    button looks like this::
    
        div.wysiwyg ul.toolbar li a.quote {
            background: url('quote-button.gif') no-repeat 0px 0px;
        }
    
    Available built-in controls are:
    
    * ``bold``: Make text bold.
    * ``italic``: Make text italic.
    * ``strikeThrough``: Make text strikethrough.
    * ``underline``: Make text underlined.
    * ``justifyLeft``: Left-align text.
    * ``justifyCenter``: Center-align text.
    * ``justifyRight``: Right-align text.
    * ``justifyFull``: Justify text.
    * ``indent``: Indent text.
    * ``outdent``: Outdent text.
    * ``subscript``: Make text subscript.
    * ``superscript``: Make text superscript.
    * ``undo``: Undo last action.
    * ``redo``: Redo last action.
    * ``insertOrderedList``: Insert ordered (numbered) list.
    * ``insertUnorderedList``: Insert unordered (bullet) list.
    * ``insertHorizontalRule``: Insert horizontal rule.
    * ``createLink``: Create a link from the selected text, by prompting the
      user for the URL.
    * ``insertImage``: Insert an image, by prompting the user for the image path.
    * ``h1``: Make text an h1 header
    * ``h2``: Make text an h2 header
    * ``h3``: Make text an h3 header
    * ``paragraph``: Make paragraph from text or h1-h6 headers
    * ``cut``: Cut selected text.
    * ``copy``: Copy selected text.
    * ``paste``: Paste from clipboard.
    * ``increaseFontSize``: Increase font size.
    * ``decreaseFontSize``: Decrease font size.
    * ``html``: Show the original textarea with HTML source. When clicked again,
      copy the textarea code back to the jWYSIWYG editor.
    * ``removeFormat``: Remove all formatting.
    * ``insertTable``: Insert a table, by prompting the user for the table
      settings.


Available Built-In Functions
----------------------------

Built-in editor functions can be triggered manually with the
``.wysiwyg("functionName"[, arg1[, arg2[, ...]]])`` call.

* addControl(name, settings)
* clear
* createLink(szURL)
    .. note::

        Include file wysiwyg.link.js to provide this function

* destroy
* document
* getContent
* insertHtml(szHTML)
* insertImage(szURL, attributes)
    .. note::

        Include file wysiwyg.image.js to provide this function

* insertTable(colCount, rowCount, filler)
    .. note::

        Include file wysiwyg.table.js to provide this function

* removeFormat
* save
* setContent

For example, if you want to save the content to original textarea, and then
remove the jWYSIWYG editor to bring original textarea back::

    $("#original").wysiwyg("save").wysiwyg("destroy")

====================================
Customizing the Editor Look and Feel
====================================


============
How it Works
============

When jWYSIWYG is called on a textarea, it does the following things:

1. Creates an additional container div to encapsulate the new editor.
2. Hides the existing textarea.
3. Creates an iframe inside the container div, populated with editor window and
   toolbar.
4. When ``saveContent()`` is called, copy its content to existing textarea.
5. Listen for ``submit`` event of closest form to apply ``saveContent()`` before
   form submition.

=======
Plugins
=======

Read document `help/docs/plugins.rst
<https://github.com/akzhan/jwysiwyg/blob/master/help/docs/plugins.rst>`_

============
Contributing
============

Read document `help/docs/contributing.rst
<https://github.com/akzhan/jwysiwyg/blob/master/help/docs/contributing.rst>`_

====================
Additional Resources
====================

Look at http://akzhan.github.com/jwysiwyg/examples/

Dive into *help* folder that contains:

* bin
    compile.sh - to compile all files into one jquery.wysiwyg.full.js
    (jquery.wysiwyg.js, controls/`*`, i18n/`*` and plugins/`*`)
* docs
    documents to help contributors
* examples
    latest examples
* lib
    to run examples and tests
* tests
    files that demonstrate some issues
