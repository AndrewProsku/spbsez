import 'particles.js';

const bgAnimation = document.querySelector('#j-particles');
const diameter = 240;
const radius = 120;

window.particlesJS.load('j-particles', '../assets/particles.json');


window.addEventListener('mousemove', (event) => {
    bgAnimation.style['clip-path'] = `circle(${diameter}px at ${event.screenX}px ${event.screenY - radius}px)`;
});
