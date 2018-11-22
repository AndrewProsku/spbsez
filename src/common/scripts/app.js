import '../styles/app.scss';


const getBodyScrollTop = function() {
    const header = document.querySelector('.j-home__header');
    const mainScreen = document.querySelector('.j-home__main-screen');
    const yOffset = self.pageYOffset;
    const maxYOffset = 100;
    const offset = yOffset ||
        (document.documentElement && document.documentElement.scrollTop) ||
        (document.body && document.body.scrollTop);

    if (offset > maxYOffset) {
        header.classList.add('is-scroll');
        mainScreen.classList.add('is-active-overlay');
    } else {
        header.classList.remove('is-scroll');
        mainScreen.classList.remove('is-active-overlay');
    }
};

window.addEventListener('scroll', getBodyScrollTop);

