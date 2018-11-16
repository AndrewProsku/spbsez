/**
 * @version 1.0
 * @author Kelnik Studios {http://kelnik.ru}
 */
/**
 * Dependencies
 */
import './disable-hover.scss';
import Utils from 'common/scripts/utils';

const body = document.body;
const disableClass = 'disable-hover';
let timer = null;


window.addEventListener('scroll', () => {
    clearTimeout(timer);

    if (!body.classList.contains(disableClass)) {
        body.classList.add(disableClass);
    }

    timer = setTimeout(() => {
        body.classList.remove(disableClass);
    }, Utils.comfortableAnimationTime());
}, false);
