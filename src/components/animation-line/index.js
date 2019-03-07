class AnimationLine {
    constructor(animationLines, mainFirstScreen) {
        this.animatedLines = animationLines;
        this.firstScreen = mainFirstScreen;
        this.firstScreenHeight = this.firstScreen.offsetHeight;
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
            this._setAnimationPosition();
        });
    }

    _repositionOnResize() {
        window.addEventListener('resize', () => {
            this.firstScreenHeight = this.firstScreen.offsetHeight;
            this._setAnimationPosition();
        });
    }

    _raiseAnimationLayer() {
        this.animatedLines.style.zIndex = 0;
    }

    _getPositionsDifference() {
        return this.firstScreenHeight - window.pageYOffset;
    }

    _comparePositions() {
        const zero = 0;

        return this._getPositionsDifference() < zero;
    }

    _setAnimationPosition() {
        if (this._comparePositions()) {
            return;
        }

        const animationPosition = this._getPositionsDifference();

        this.animatedLines.style.top = `${animationPosition}px`;
    }
}

export default AnimationLine;
