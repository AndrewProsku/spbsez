class GlideCarousel {
    constructor() {
        this.dots = document.querySelector('.j-glide-dots');
        this.arrows = document.querySelectorAll('.j-glide-arrow');
        this.dotsShowClass = 'glide__cust-dots-invisible';
        this.oldImageHeight = null;
    }

    init() {
        this.setControlsPosition();
    }

    setControlsPosition() {
        this.setDotsPosition();
        this.setArrowPosition();
    }

    _getActiveImageHeight() {
        const image = document.querySelector('.glide__slide--active .j-glide-slide-img');

        if (!image) {
            return false;
        }

        if (this.oldImageHeight === image.offsetHeight) {
            return false;
        }

        return image.offsetHeight;
    }

    _isMobile() {
        const tabletBreakpoint = 680;

        return document.documentElement.clientWidth < tabletBreakpoint;
    }

    setDotsPosition() {
        const imgHeight = this._getActiveImageHeight();
        const marginBottom = 35;
        const marginBottomMobile = 8;

        if (!imgHeight) {
            return;
        }

        this.dots.classList.add(this.dotsShowClass);
        this.dots.style.top = this._isMobile() ?
            `${imgHeight + marginBottomMobile}px` :
            `${imgHeight - marginBottom}px`;
    }

    setArrowPosition() {
        const imgHeight = this._getActiveImageHeight();
        const half = 2;

        if (!imgHeight) {
            return;
        }

        this.arrows.forEach((arrow) => {
            arrow.style.top = `${imgHeight / half}px`;
        });
    }
}

export default GlideCarousel;

