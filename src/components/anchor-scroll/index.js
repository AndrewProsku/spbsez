/**
 * @version 1.2
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/anchor-scroll.html documentation
 */

import $ from 'jquery';

class Anchor {
    /**
     * @param {Object} options - outer options
     */
    init(options) {
        const defaultSpeed = 300;
        const defaultFrameCount = 20;

        this.target = options.target;
        this.animationTime = options.speed || defaultSpeed;
        this.framesCount = options.framesCount || defaultFrameCount;

        if (!this.target) {
            return;
        }

        this._bindEvents();
    }

    _bindEvents() {
        const $body = $('body, html');
        const headerHeight = document.querySelector('.j-home__header').clientHeight;
        const scrollTarget = $(this.target.getAttribute('href')).offset().top - headerHeight;

        this.target.addEventListener('click', (event) => {
            event.preventDefault();
            $body.animate({
                scrollTop: scrollTarget
            }, 'slow');
        });
    }
}

export default Anchor;
