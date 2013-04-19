/*******************************************************************************
 jQuery Column View Plugin
 Copyright (c) 2010-2011. John Obelenus 
 <jobelenus@gmail.com>
 https://github.com/jobelenus/columnview

 Licences: MIT, GPL
 http://www.opensource.org/licenses/mit-license.php
 http://www.gnu.org/licenses/gpl.html


 Update by Thomas Blum
 <thomas.blum@redaxo.de>

 new:
 - option.tag = 'span' >> (data-remote attribute instead of href for url)

 ******************************************************************************/


(function ( $ ) {
    var methods = {
        columnview: null,
        options: null,
        is_int: function(s) {
            return (s.toString().search(/^-?[0-9]+$/) == 0);
        },
        set_url: function(node, url) {
            node.data('url', url);
        },
        get_url: function(node) {
            return node.data('url');
        },
        get_num: function(node) {
            return parseInt(node.data('num'));//make sure this is an integer
        },
        inc_num: function(node) {
            node.data('num', methods.get_num(node)+1);
            return methods.get_num(node);
        },
        dec_num: function(node) {
            node.data('num', methods.get_num(node)-1);
            return methods.get_num(node);
        },
        expand: function(url, html, col) {
            var columnview = methods.columnview;
            var seek = null;
            columnview.find('.column').each(function() {
                var colnum = methods.get_num($(this));
                if(colnum == col) {
                    seek = $(this);
                    seek.empty();
                } else if(colnum > col) {
                    $(this).remove(); //kill leaves that no longer apply to new child
                    methods.dec_num(columnview);
                }
            });
            if(!seek) {
                seek = methods.create_column();
                seek.appendTo(columnview);
            }
            seek.html(html);
            methods.set_url(seek, url);
            methods.setup_links(seek);
            seek.scrollTop(0);
        },
        create_column: function() {
            var columnview = methods.columnview;
            var num = methods.inc_num(columnview); //next leaf in columns
            var div = $('<div class="column" style="float:left;"></div>');
            if(methods.options && methods.options.columns)
                div.css('width', methods.options.columns[num]); //apply a width if we have one
            div.data('num', num);
            columnview.data('num', num);
            return div;
        },
        refresh_column: function(column) {
            var columnview = methods.columnview;
            if(methods.is_int(column))
                column = $(columnview.find('.column').get(column));
            var url = methods.get_url(column);
            if(url) {
                $.get(url, function(html) {
                    column.html(html);
                    methods.setup_links(column);
                    column.scrollTop(0);
                });
            }
        },
        setup_links: function(column) {
            var tag = 'a';
            var attr = 'href';
            var find = 'a:not(.ignore-link)';
            if(methods.options && methods.options.tag) {
                tag  = methods.options.tag;
                attr = 'data-remote';
                find = tag + '[' + attr + ']';
            }
            column.find(find).each(function() {
                $(this).click(function(evt) {
                    column.find(tag + '.selected').removeClass('selected').trigger('columnview-deselected');
                    var num = methods.get_num(column);
                    var url = $(this).attr(attr);
                    $.get(url, function(html) {
                        methods.expand(url, html, num+1); //expand into the next column
                    });
                    $(this).addClass('selected').trigger('columnview-selected');
                    evt.preventDefault();
                    return false;
                });
            });
        },
        init: function(options) {
            methods.options = options;
            var columnview = methods.columnview;
            columnview.data('num', 0); //init column counter
            div = methods.create_column();
            div.html(columnview.contents().detach()).appendTo(columnview); //replace contents into new column
            methods.setup_links(div);
        }
    };

    $.fn.columnview = function(method) {
        methods.columnview = this;

        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.columnview' );
        } 

        return this;
    };
})( jQuery );
