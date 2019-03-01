import 'particles.js';

const node = document.querySelector('#j-particles');

if (node) {
    const id = 'j-particles';
    const json = './assets/particles.json';

    const animateBackground = function(element) {
        const diameter = 240;
        const radius = 120;

        window.addEventListener('mousemove', (event) => {
            element.style['clip-path'] = `circle(${diameter}px at ${event.screenX}px ${event.screenY - radius}px)`;
        });
    };

    window.particlesJS.load(id, json);

    animateBackground(node);
}
