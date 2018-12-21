class AnimationTest {
    init() {
        this._animationBlock();
    }

    _animationBlock() {
        const animationBlock = document.querySelector('.j-animation-block');
        const addClass = 5000;
        const removeClass = 3000;

        const animationBlockDeleteClass = () => {
            animationBlock.classList.remove('go-animation');
        };

        const animationBlockAddClass = () => {
            animationBlock.classList.add('go-animation');
            setTimeout(animationBlockDeleteClass, removeClass);
        };

        setInterval(animationBlockAddClass, addClass);
    }
}

export default AnimationTest;
