import '../styles/app.scss';


const getBodyScrollTop = function() {
    const header = document.querySelector('.l-home__header');
    const yOffset = self.pageYOffset;
    const maxYOffset = 100;
    const offset = yOffset ||
        (document.documentElement && document.documentElement.scrollTop) ||
        (document.body && document.body.scrollTop);

    if (offset > maxYOffset) {
        header.classList.add('is-scroll');
    } else {
        header.classList.remove('is-scroll');
    }
};

window.addEventListener('scroll', getBodyScrollTop);

