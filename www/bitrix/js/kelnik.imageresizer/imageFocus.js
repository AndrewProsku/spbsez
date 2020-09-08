(function ($) {
    $.fn.imageFocus = function () {
        var wrap = document.getElementById(this.attr('id'));

        if (typeof wrap === 'undefined') {
            return;
        }

        wrap.addEventListener('dragstart', dragstartHandler, false);
        wrap.addEventListener('dragenter', dragenterHandler, false);
        wrap.addEventListener('dragleave', dragleaveHandler, false);
        wrap.addEventListener('dragover', dragoverHandler, false);
        wrap.addEventListener('dragend', dragendHandler, false);
        wrap.addEventListener('drop', dropHandler, false);

        initFocus();

        function dragstartHandler(e)
        {
            var target = e.target;
            var dataTransfer = e.dataTransfer;

            target.style.opacity = '0.5';
            source = target;

            dataTransfer.effectAllowed = 'move';
            dataTransfer.setData('text/html', target.innerHTML.trim());
        }

        function dragenterHandler(e)
        {
            if (e.preventDefault) {
                e.preventDefault();
            }

            return false;
        }

        function dragleaveHandler(e)
        {
            return false;
        }

        function dragoverHandler(e)
        {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            return false;
        }

        function dragendHandler(e)
        {
            return false;
        }

        function dropHandler(e)
        {
            e.stopPropagation;

            source.style.opacity = 1;
            setPosition(e.layerX, e.layerY);

            if (wrap.getAttribute('data-ajax') === 'true') {
                var elementID = wrap.getAttribute('data-element-id');
                var data = {
                    sessid: document.getElementById('sessid').value,
                    FIELDS: {},
                    FIELDS_OLD: {},
                    save: ['Y', 'Сохранить']
                };

                data.FIELDS_OLD[elementID] = {};
                data.FIELDS_OLD[elementID]['FOCUS'] = '';

                data.FIELDS[elementID] = {};
                data.FIELDS[elementID]['FOCUS'] = getPercents(wrap.clientWidth, wrap.clientHeight, e.layerX, e.layerY);

                $.post(
                    '/bitrix/admin/admin_helper_route.php?mode=frame&lang=ru&module=kelnik.imageresizer&view=image_list&restore_query=Y&entity=images&PAGEN_1=1&SIZEN_1=20',
                    data
                );

                return false;
            }

            document.getElementById(wrap.getAttribute('data-input-id')).value =
                getPercents(wrap.clientWidth, wrap.clientHeight, e.layerX, e.layerY);

            return false;
        }

        function initFocus()
        {
            var data = typeof wrap.getAttribute('data-coords') === 'string'
                        ? wrap.getAttribute('data-coords').split(',')
                        : document.getElementById(wrap.getAttribute('data-input-id')).value.split(',');

            if (!data.length) {
                return false;
            }

            data = getCoords(wrap.clientWidth, wrap.clientHeight, data);

            setPosition(data.shift(), data.shift());
        }

        function getPercents(imageWidth, imageHeight, x, y)
        {

            x = 100 / (imageWidth / x);
            y = 100 / (imageHeight / y);

            return Math.round(x) + ',' + Math.round(y);
        }

        function getCoords(imageWidth, imageHeight, percents)
        {
            imageWidth = imageWidth * parseInt(percents[0]) * 0.01;
            imageHeight = imageHeight * parseInt(percents[1]) * 0.01;

            return [Math.round(imageWidth), Math.round(imageHeight)];
        }

        function setPosition(x, y)
        {
            var dot = wrap.getElementsByClassName('kelnik-image-focus_dot');

            if (!dot.length) {
                return;
            }

            dot = Array.from(dot).shift();

            dot.style.left = (x - dot.clientWidth) + 'px';
            dot.style.top = (y - dot.clientHeight) + 'px';
        }
    }
})(jQuery);
