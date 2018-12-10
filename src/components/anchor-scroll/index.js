/**
 * @version 1.2
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/anchor-scroll.html documentation
 */

const bindEvents = function(anchorLinks, animateTime, framesCount) {
    const anchors = [].slice.call(document.querySelectorAll(anchorLinks));
    const topPosition = 0;

    anchors.forEach((anchorLink) => {
        anchorLink.addEventListener('click', (event) => {
            event.preventDefault();

            const coordY = document.querySelector(anchorLink.getAttribute('href')).getBoundingClientRect().top;

            const scroller = setInterval(() => {
                const scrollBy = coordY / framesCount;

                if (scrollBy > window.pageYOffset - coordY &&
                    window.innerHeight + window.pageYOffset < document.body.offsetHeight) {
                    window.scrollBy(topPosition, scrollBy);
                } else {
                    window.scrollTo(topPosition, coordY);
                    clearInterval(scroller);
                }
            }, animateTime / framesCount);

            window.addEventListener('wheel', () => {
                clearInterval(scroller);
            });
        });
    });
};


class Anchor {
    /**
     * @constructor
     * @param {Object} options - outer options
     */
    constructor(options) {
        const defaultSpeed = 300;
        const defaultFrameCount = 20;

        this.target = options.target;
        this.animationTime = options.speed || defaultSpeed;
        this.framesCount = options.framesCount || defaultFrameCount;

        if (!this.target) {
            return;
        }

        bindEvents(this.target, this.animationTime, this.framesCount);
    }
}

export default Anchor;
