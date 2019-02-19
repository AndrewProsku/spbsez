import 'particles.js';

const bgAnimation = document.querySelector('#j-particles');
const bgAnimationv2 = document.querySelector('#j-particles_v2');

const animateBackground = function(tag, json) {
    const diameter = 240;
    const radius = 120;

    window.particlesJS.load(tag, json);


    window.addEventListener('mousemove', (event) => {
        bgAnimation.style['clip-path'] = `circle(${diameter}px at ${event.screenX}px ${event.screenY - radius}px)`;
    });
};

if (bgAnimation) {
    animateBackground('j-particles', '../assets/particles.json');
} else if (bgAnimationv2) {
    animateBackground('j-particles_v2', '../assets/particles_v2.json');
}
