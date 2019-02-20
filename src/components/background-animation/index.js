import 'particles.js';

const body = document.body;
const html = document.documentElement;
const backgroundHeight =
    Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);

const map = {
    0: {
        type: 'dynamic',
        div : document.querySelector('#j-particles'),
        tag : 'j-particles',
        json: '../assets/particles.json'
    },
    1: {
        type: 'dynamic',
        div : document.querySelector('#j-particles_v2'),
        tag : 'j-particles_v2',
        json: '../assets/particles_v2.json'
    },
    2: {
        type: 'static',
        div : document.querySelector('#j-particles_static'),
        tag : 'j-particles_static',
        json: '../assets/particles_static.json'
    }
};

const animateBackground = function(element) {
    const diameter = 240;

    window.addEventListener('mousemove', (event) => {
        element.style['clip-path'] = `circle(${diameter}px at ${event.pageX}px ${event.pageY}px)`;
    });
};

for (const particles in map) {
    if (Object.prototype.hasOwnProperty.call(map, particles) && map[particles].div) {
        map[particles].div.style.height = `${backgroundHeight}px`;

        window.particlesJS.load(map[particles].tag, map[particles].json);

        if (map[particles].type !== 'static') {
            animateBackground(map[particles].div);
        }
    }
}
