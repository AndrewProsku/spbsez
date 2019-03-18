class AnimationLine {
    constructor(animationLines, mainFirstScreen) {
        this.animatedLines = animationLines;
        this.firstScreen = mainFirstScreen;
        this.firstScreenHeight = this.firstScreen.offsetHeight;
        this.firstScreenBottom = this.firstScreen.getBoundingClientRect().top + this.firstScreen.offsetHeight;
    }

    init() {
        this._positionOnInit();
        this._repositionOnScroll();
        this._repositionOnResize();
        this._raiseAnimationLayer();
    }

    _positionOnInit() {
        this._setAnimationPosition();
    }

    _repositionOnScroll() {
        document.addEventListener('scroll', () => {
            this._computeFirstScreenBottom();
            this._setAnimationPosition();
        });
    }

    _repositionOnResize() {
        window.addEventListener('resize', () => {
            this.firstScreenHeight = this.firstScreen.offsetHeight;
            this._computeFirstScreenBottom();
            this._setAnimationPosition();
        });
    }

    _raiseAnimationLayer() {
        this.animatedLines.style.zIndex = 1;
    }

    _computeFirstScreenBottom() {
        this.firstScreenBottom = this.firstScreen.getBoundingClientRect().top + this.firstScreen.offsetHeight;
    }

    _isFirstScreenVisible() {
        const zero = 0;
        // this._computeFirstScreenBottom();

        return this.firstScreenBottom >= zero;
    }

    _setAnimationPosition() {
        if (!this._isFirstScreenVisible()) {
            return;
        }

        this.animatedLines.style.top = `${this.firstScreenBottom}px`;
    }
}

export default AnimationLine;
