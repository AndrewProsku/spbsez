/**
 * @version 2.0alpha
 * @author Kelnik Studios {http://kelnik.ru}
 * @link https://kelnik.gitbooks.io/kelnik-documentation/content/front-end/components/пока нет documentation
 */

/**
 * DEPENDENCIES
 */

/* eslint-disable */
import Hammer from 'hammerjs';
import sliderTemplate from './slider.twig';
import Utils from 'common/scripts/utils';

class Slider {
    /**
     * Инициализация параметров
     */
    init(options) {
        this.gallery = document.querySelector(options.target);
        this.transitionTime = options.transitionTime || 300;
        this.infinite = options.infinite || false;
        this.autoplay = options.autoplay || false;
        this.autoplaySpeed = options.autoplaySpeed || 3000;
        this.childrens = Array.from(this.gallery.children);
        this.itemsCount = this.childrens.length;
        this.activeSlide = 1;
        this.width = 0;
        this.position = 0;

        Utils.clearHtml(this.gallery);
        Utils.insetContent(this.gallery, sliderTemplate());

        this.createGallery();
    }

    /**
     * Главный метод. Создает весь слайдер.
     */
    createGallery() {
        if (!this.gallery) {
            console.error('Такого контейнера для слайдера не существует!');
            return false;
        }

        this.getElements();
        this.createDots();
        this.bindEvents();
        this.appendChilds();
        this.setHeight();
        this.dotActivate();
        this.ready();
        this.autoplayStart();
    }

    /**
     * Получаем элементы слайдера.
     */
    getElements() {
        this.dotsWrapper = this.gallery.querySelector('.b-slider__dots');
        this.wrapper = this.gallery.querySelector('.b-slider__wrapper');
        this.arrowLeft = this.gallery.querySelector('.slider__arrow-left');
        this.arrowRight = this.gallery.querySelector('.slider__arrow-right');
    }

    /**
     * Создаёт точки в html.
     */
    createDots() {
        for (let i = 0; i < this.itemsCount; i++) {
            this.dotsWrapper.append(this.createDot());
        }
    }

    /**
     * Создаёт точку.
     * @return {HTMLButtonElement}
     */
    createDot() {
        const dot = document.createElement('button');
        dot.classList.add('b-slider__dot');

        return dot;
    }


    /**
     * Навешивает события на контролы
     */
    bindEvents() {
        const touchEvent = new Hammer(this.gallery);

        touchEvent.on('swiperight', this.isSlideLeft.bind(this));
        touchEvent.on('swipeleft', this.isSlideRight.bind(this));

        this.arrowLeft.addEventListener('click', this.isSlideLeft.bind(this), false);
        this.arrowRight.addEventListener('click', this.isSlideRight.bind(this), false);

        this.dotsWrapper.addEventListener('click', (event) => {
            let index = Utils.getElementIndex(event.target) + 1;

            this.activeSlide = index;
            this.dotActivate();
            this.slideTo(this.goTo(index));
        });

        window.addEventListener('resize', () => {
            this.width = this.gallery.offsetWidth;
            this.position = -(this.width * (this.activeSlide - 1));
            this.slideTo(this.position);
            this.setHeight();
        });
    }

    /**
     * Добавляет слайды.
     */
    appendChilds() {
        for (let i = 0; i < this.childrens.length; i++) {
            const item = document.createElement('div');

            item.classList.add('b-slider__item');
            item.append(this.childrens[i]);
            this.wrapper.append(item);
            this.width = this.wrapper.offsetWidth;
        }
    }

    /**
     * Определяет максимальную высоту слайдов, и устанавливает ее обертке.
     */
    setHeight() {
        const newArray = this.childrens.map((item) => {
            const image = item.querySelector('img');
            return image.clientHeight;
        });

        const maxImageHeight = Math.max.apply(null, newArray);

        this.wrapper.style.height = `${maxImageHeight}px`;
    };

    /**
     * Меняет активное значение у точки.
     */
    dotActivate() {
        this.allDots = this.gallery.querySelectorAll('.b-slider__dot');
        const activeDotClass = 'b-slider__dot_is_active';

        this.allDots.forEach((element, index) => {
            if (this.activeSlide - 1 === index) {
                element.classList.add(activeDotClass);
                this.transitionControl();

            } else {
                element.classList.remove(activeDotClass);
            }
        });
    };

    /**
     * Показывает слайдер когда все методы выполнятся.
     */
    ready() {
        this.gallery.classList.add('b-slider_is_ready');
    }

    /**
     * Перелистывает к переданной позиции.
     * @param {Number} toPosition - позиция слайдера по X оси.
     */
    slideTo(toPosition) {
        this.wrapper.style.transform = `translateX(${toPosition}px)`;
    }

    /**
     * Листает слайдер вправо.
     */
    slideLeft() {
        this.transitionControl();
        this.slideTo(this.position);
        this.activeSlide -= 1;
        this.dotActivate();
    }

    /**
     * Листает слайдер влево.
     */
    slideRight() {
        this.transitionControl();
        this.slideTo(this.position);
        this.activeSlide += 1;
        this.dotActivate();
    }

    /**
     * Проверяет можно ли листать влево (если не нужен инфинити скролл, то в if просто вернуть false).
     */
    isSlideLeft() {
        if (this.position >= 0) {

            if (!this.infinite) {
                return false;
            }
            this.position = -((this.itemsCount - 1) * this.width);
            this.activeSlide = this.itemsCount + 1;
        } else {
            this.position += this.width;
        }
        this.slideLeft();
    }

    /**
     * Проверяет можно ли листать вправо (если не нужен инфинити скролл, то в if просто вернуть false).
     */
    isSlideRight() {
        if (this.position <= -(this.width * (this.itemsCount - 1))) {

            if (!this.infinite) {
                return false;
            }
            this.position = 0;
            this.activeSlide = 0;
        } else {
            this.position -= this.width;
        }
        this.slideRight();
    }

    /**
     * Определяет новую позицию слайда в зависимости на какую точку нажали.
     * @param value {number} - Порядковый номер слайда.
     * @return {number} - Новая позиция.
     */
    goTo(value) {
        let newPosition = -(this.width * (value - 1));
        this.position = newPosition;
        return newPosition;
    }

    /**
     * Добавляет transition только на время слайда (чтобы небыло анимации при ресайзе)
     */
    transitionControl() {
        this.wrapper.style.transitionDuration = `${100 / this.transitionTime}s`;

        setTimeout(() => {
            this.wrapper.style.removeProperty('transition-duration');
        }, this.transitionTime);
    }

    /**
     * Метод автопролистывания слайдов.
     * @return {boolean} - если автоплэй не задан либо false, то сетинтервал не работает
     */
    autoplayStart() {
        if (!this.autoplay) {
            return false;
        }

        setInterval(() => {
            this.isSlideRight();
        }, this.autoplaySpeed);
    }
}

export default Slider;
/* eslint-enable */
