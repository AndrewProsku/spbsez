import './particles.js';

const isDesktop = window.matchMedia('(min-width: 1280px)');

class Particles {
    constructor(node) {
        this.node = node;
        this.query = isDesktop.matches;
    }

    init() {
        if (!this.node) {
            return;
        }

        window.particlesJS.load('j-particles', this._bindEvents.bind(this));
    }

    _bindEvents() {
        this._clipPath = this._clipPath.bind(this);

        if (this.query) {
            window.addEventListener('mousemove', this._clipPath);
        }

        window.addEventListener('resize', () => {
            if (this.query === isDesktop.matches) {
                return;
            }

            this.query = isDesktop.matches;

            const toggleEventListener = this.query ? window.addEventListener : window.removeEventListener;

            toggleEventListener('mousemove', this._clipPath);
        });
    }

    _clipPath(event) {
        const radius = 120;
        const diameter = 240;

        this.node.style['clip-path'] = `circle(${diameter}px at ${event.screenX}px ${event.screenY - radius}px)`;
    }
}

export default Particles;
