export default class Canvas {
    constructor() {
        /**
         * Родительский контейнер в которой должен вписаться svg
         * @type {Node}
         */
        this.parrent = null;

        /**
         * SVG который мы будем подгонять под родителя
         * @type {Node}
         */
        this.svg = null;
    }

    init(parrent) {
        this.parrent = parrent;
        this.svg = parrent.querySelector('svg');

        this.calcaulate();
        this.resize();
    }

    resize() {
        window.addEventListener('resize', () => {
            this.calcaulate();
        });
    }

    calcaulate() {
        const containerHeight = this.parrent.offsetHeight;
        const containerWidth = this.parrent.offsetWidth;
        const imageWidth = 1920;
        const imageHeight = 1440;
        const imageRatio = imageHeight / imageWidth;
        const containerRatio = containerHeight / containerWidth;
        const half = 2;
        let newHeight = imageHeight;
        let newWidth = imageWidth;

        if (containerRatio > imageRatio) {
            newHeight = containerHeight;
            newWidth = containerHeight / imageRatio;
        } else {
            newWidth = containerWidth;
            newHeight = containerWidth / imageRatio;
        }

        this.svg.style.left = `${-(newWidth - containerWidth) / half}px`;
        this.svg.style.top = `${-(newHeight - containerHeight) / half}px`;
        this.svg.style.width = `${newWidth}px`;
        this.svg.style.height = `${newHeight}px`;
    }
}
