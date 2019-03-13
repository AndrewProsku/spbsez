'use strict';

(function ($) {
    $.fn.treeGrid = function (options) {
        options = $.extend({
            table: '#tree-table',
            url: '',
            path: window.location.pathname,
            idField: 'id',
            treeField: 'name',
            useCookie: true,
            columns: []
        }, options);

        if (options.path.substr(options.path.length - 1, 1) != '/') {
            options.path += '/';
        }

        function arrayUnique(arr) {
            var tmp = {};
            var out = [];
            for (var i = 0, n = arr.length; i < n; ++i) {
                if (!tmp[arr[i]]) {
                    tmp[arr[i]] = true;out.push(arr[i]);
                }
            }
            return out;
        }

        function openChildren(obj, pid) {
            $(obj).find('tr[data-parent-id=' + pid + ']').each(function () {
                //$(this).closest('tr').fadeIn();
                slideDownTr(this);
                openChildren(obj, $(this).data('row-id'));
            });

            $(obj).find('tr[data-row-id=' + pid + '] > td > div.kelnik-tree-block.loaded').addClass('open');
            $(obj).find('tr[data-row-id=' + pid + '] > td a').show();
        }

        function closeChildren(obj, pid) {
            $(obj).find('tr[data-parent-id=' + pid + ']').each(function () {
                closeChildren(obj, $(this).data('row-id'));
                //$(this).closest('tr').fadeOut();
                slideUpTr(this);
            });

            // save opened in cookie
            //
            if (options.useCookie && typeof Cookies !== 'undefined') {
                delCookie(pid);
            }
        }

        function slideUpTr(tr) {
            $(tr).closest('tr').children('td').animate({ padding: 0 }).children().slideUp(function () {
                $(tr).closest('tr').hide();
            });
        }

        function slideDownTr(tr) {
            $(tr).show().find('td > div').slideDown(function () {
                $(this).removeAttr('style');
                $(tr).removeAttr('style');
            });
            $(tr).find('td').removeAttr('style');
        }

        function getData(obj, id, level) {
            $.post(
                options.url,
                { id: id },
                function (data) {
                    for (var i in data) {
                        var str = '<tr class="adm-list-table-row" style="display:none" data-row-id="' + data[i][options.idField] + '" data-parent-id="' + id + '" data-level="' + level + '">';

                        for (var j in options.columns) {
                            var val = data[i][options.columns[j].field];

                            if (typeof val === 'undefined') {
                                val = '';
                            }

                            if (options.columns[j].field == options.treeField) {
                                str += '<td class="adm-list-table-cell" style="padding:0" ';

                                for (var k in options.columns[j].attributes) {
                                    str += ' ' + k + '="' + options.columns[j].attributes[k] + '" ';
                                }

                                str += '><div style="display:none" ' + 'class="kelnik-tree-block row collapse">' + '<div class="kelnik-tree-left">';

                                if (level > 0) {
                                    for (var _k = 0; _k < level; _k++) {
                                        str += '<span class="kelnik-tree-space"></span>'; // eslint-disable-line
                                    }
                                }

                                if (typeof data[i].quantity != 'undefined' && data[i].quantity > 0) {
                                    str += '<i class="kelnik-tree-corner fa fa-caret-right" aria-hidden="true">' + '</i><a href="#" class="' + 'kelnik-tree-sub fa fa-folder" ' + 'aria-hidden="true"></a></div>';
                                } else if (data[i].quantity == 0) {
                                    str += '<i class="kelnik-tree-no-sub fa fa-folder is-empty" aria-hidden="true">' + '</i></div>';
                                } else {
                                    str += '<i class="kelnik-tree-no-sub fa fa-file is-file" aria-hidden="true">' + '</i></div>';
                                }

                                str += '<div class="kelnik-tree-right">' + val + '</div>';
                                str += '</div></td>';
                            } else {
                                str += '<td class="adm-list-table-cell" style="padding:0"';

                                for (var _k2 in options.columns[j].attributes) {
                                    str += ' ' + _k2 + '="' + options.columns[j].attributes[_k2] + '" ';
                                }

                                str += '>' + val + '</td>';
                            }
                        }

                        str += '</tr>';

                        if (level > 0) {
                            if ($(obj).find('tr[data-row-id=' + data[i][options.idField] + ']').length < 1) {
                                $(obj).find('tr[data-row-id=' + id + ']').after(str);
                            }
                        } else {
                            $(obj).append(str);
                        }

                        $(obj).find('tr:hidden[data-parent-id=' + id +']').each(function () {
                            slideDownTr(this);
                        });
                    }

                    $(obj).find('tr[data-row-id=' + id + '] > td > div.kelnik-tree-block').addClass('open loaded');

                    if (options.useCookie && typeof Cookies !== 'undefined') {
                        openCookie(obj);
                    }
                },
                'json'
            );
        }

        function addCookie(id) {
            var open = Cookies.get('kelnik_tree_grid');

            open = typeof open === 'undefined' ? [] : open.split(',');

            open.push(id);
            open = arrayUnique(open);
            open = open.join(',');

            Cookies.set('kelnik_tree_grid', open, { expires: 7, path: options.path });
        }

        function delCookie(id) {
            var open = Cookies.get('kelnik_tree_grid');

            if (typeof open === 'undefined') {
                return;
            }

            var newOpen = [];
            open = open.split(',');

            for (var i in open) {
                if (!parseInt(open[i])) {
                    continue;
                }
                if (parseInt(open[i]) != parseInt(id)) {
                    newOpen.push(open[i]);
                }
            }

            if (newOpen.length != open.length) {
                newOpen = newOpen.join(',');
                Cookies.set(
                    'kelnik_tree_grid', newOpen, { expires: 7, path: options.path });
            }
        }

        function openCookie(obj) {
            var open = Cookies.get('kelnik_tree_grid');

            if (typeof open !== 'undefined') {
                open = open.split(',');
                for (var i in open) {
                    if (parseInt(open[i]) > 0) {
                        var block = $(obj).find('tr[data-row-id=' + open[i] + ']').find('td > div.kelnik-tree-block');

                        if (!$(block).hasClass('open') && !$(block).hasClass('loaded')) {
                            $(block).find('a.kelnik-tree-sub').click();
                        }
                    }
                }
            }
        }

        return this.each(function () {
            var obj = this;

            $(this).on('click', 'div.kelnik-tree-block > div > a.kelnik-tree-sub', function (event) {
                event.preventDefault();
                var tr = $(this).closest('tr');
                var dv = $(this).closest('.kelnik-tree-block');

                if ($(dv).hasClass('open')) {
                    $(dv).removeClass('open');
                    closeChildren(obj, $(tr).data('row-id'));
                } else {
                    if (!$(dv).hasClass('loaded')) {
                        getData(obj, $(tr).data('row-id'), parseInt($(tr).data('level')) + 1);
                    } else {
                        openChildren(obj, $(tr).data('row-id'));
                    }

                    // save opened in cookie
                    //
                    if (options.useCookie && typeof Cookies !== 'undefined') {
                        addCookie($(tr).data('row-id'));
                    }
                }
            });

            getData(this, 0, 0);
        });
    };
})(jQuery);
