import 'particles.js';

const isDesktop = window.matchMedia('(min-width: 1280px)');

const animation = {
    "particles": {
        "number": {
            "value": 80,
            "density": {
                "enable": true,
                "value_area": 800
            }
        },
        "color": {
            "value": "#a0a0a0"
        },
        "shape": {
            "type": "circle",
            "stroke": {
                "width": 0,
                "color": "#a0a0a0"
            },
            "polygon": {
                "nb_sides": 3
            },
            "image": {
                "src": "img/github.svg",
                "width": 100,
                "height": 100
            }
        },
        "opacity": {
            "value": 1,
            "random": false,
            "anim": {
                "enable": false,
                "speed": 0,
                "opacity_min": 0.98,
                "sync": false
            }
        },
        "size": {
            "value": 1,
            "random": false,
            "anim": {
                "enable": false,
                "speed": 40,
                "size_min": 0.1,
                "sync": false
            }
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#a0a0a0",
            "opacity": 0.4,
            "width": 0.75
        },
        "move": {
            "enable": true,
            "speed": 6,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
            }
        }
    },
    "interactivity": {
        "detect_on": "window",
        "events": {
            "onhover": {
                "enable": true,
                "mode": "repulse"
            },
            "onclick": {
                "enable": false,
                "mode": "push"
            },
            "resize": true
        },
        "modes": {
            "grab": {
                "distance": 250,
                "line_linked": {
                    "opacity": 0.075
                }
            },
            "bubble": {
                "distance": 400,
                "size": 40,
                "duration": 2,
                "opacity": 8,
                "speed": 3
            },
            "repulse": {
                "distance": 50,
                "duration": 0.4
            },
            "push": {
                "particles_nb": 4
            },
            "remove": {
                "particles_nb": 2
            }
        }
    },
    "retina_detect": true
};

class Particles {
    constructor(node) {
        this.node = node;
        this.query = isDesktop.matches;
    }

    isIE(){
        return document.documentMode || /Edge\//.test(navigator.userAgent);
    }

    init() {
        if (!this.node) {
            return;
        }

        window.particlesJS('j-particles', animation);

        this._bindEvents();
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
        const x = event.screenX;
        const y = event.screenY;


        if (this.isIE()) {
            this.node.style['clip'] = `rect(${y - diameter}px, ${x + radius}px, ${y}px, ${x - radius}px)`;
        } else {
            this.node.style['clip-path']         = `circle(${diameter}px at ${x}px ${y - radius}px)`;
            this.node.style['-webkit-clip-path'] = `circle(${diameter}px at ${x}px ${y - radius}px)`;
            // eslint-disable-line
            this.node.offsetWidth; //принудительный триггер reflow, чтобы в прекрасном Сафари триггерился рендер смещения
        }
    }
}

export default Particles;
