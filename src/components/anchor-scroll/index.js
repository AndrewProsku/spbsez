/**
 * @version 1.2
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/anchor-scroll.html documentation
 */

import $ from 'jquery';
import Utils from '../../common/scripts/utils';

class Anchor {
    constructor() {
        this.$body = $('body, html');
        this.headerSelector = '.j-home__header';
    }

    init(options) {
        this.targets = Array.from(options.targets);
        this.preventDefault = Utils.keyExist(options, 'preventDefault') ? options.preventDefault : true;

        if (!this.targets) {
            return;
        }

        this._bindEvents();
        this.urlHashScroll();
    }

    _bindEvents() {
        this.targets.forEach((target) => {
            target.addEventListener('click', (event) => {
                if (this.preventDefault) {
                    event.preventDefault();
                }

                const targetID = target.getAttribute('href') ?
                    target.getAttribute('href') :
                    target.dataset.scrollTarget;

                this._goScroll(targetID);
            });
        });
    }

    urlHashScroll(duration = 10) {
        const href = window.location.hash;

        if (!href) {
            return;
        }

        this._goScroll(href, duration);
    }

    _goScroll(href, duration = 'slow') {
        const hrefTarget = $(href);
        const scrollTarget = this._getScrollTop(hrefTarget);

        this._scroll(scrollTarget, duration);
    }

    _getScrollTop(href) {
        const headerHeight = document.querySelector(this.headerSelector).clientHeight;
        const hrefTarget = $(href);

        if (!hrefTarget.length) {
            return false;
        }

        return hrefTarget.offset().top - headerHeight;
    }

    _scroll(scrollTarget, duration = 'slow') {
        this.$body.animate({
            scrollTop: scrollTarget
        }, duration);
    }
}

export default Anchor;
