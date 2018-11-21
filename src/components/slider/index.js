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
import includes from 'array-includes';
import Mediator from 'common/scripts/mediator';

const mediator = new Mediator();

class Slider {
    /**
     * Инициализация параметров
     */
    init(options) {
        this.gallery = options.target;
        this.transitionTime = options.transitionTime || 300;
        this.infinite = options.infinite || false;
        this.autoplay = options.autoplay || false;
        this.autoheight = options.autoheight || false;
        this.autoplaySpeed = options.autoplaySpeed || 3000;
        this.childrens = Array.from(this.gallery.children);
        this.itemsCount = this.childrens.length;
        this.activeSlide = 1;
        this.width = 0;
        this.position = 0;
        this.currentControl = this.arrowLeft;
        this.isTouch = Utils.isTouch();

        //Utils.clearHtml(this.gallery); //почему то в IE очищает - this.childrens
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
        if (!this.isTouch) {
            this.bindMouseEvents();
        }

        this.appendChilds();
        this.setHeight();
        this.dotActivate();
        this.ready();
        this.autoplayStart();
        this.positionControls();
    }

    /**
     * Получаем элементы слайдера.
     */
    getElements() {
        this.dotsWrapper = this.gallery.querySelector('.b-slider__dots');
        this.arrowsWrapper = this.gallery.querySelector('.b-slider__arrows');
        this.wrapper = this.gallery.querySelector('.b-slider__wrapper');
        this.arrows =Array.from(this.gallery.querySelectorAll('.b-slider__arrow'));
        this.arrowLeft = this.gallery.querySelector('.slider__arrow-left');
        this.arrowRight = this.gallery.querySelector('.slider__arrow-right');
        this.mediumDescription =  this.gallery.querySelector('.b-medium-info-slider__description');
    }

    /**
     * Создаёт точки в html.
     */
    createDots() {
        for (let i = 0; i < this.itemsCount; i++) {
            this.dotsWrapper.appendChild(this.createDot());
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

        this.wrapper.addEventListener('click', this.onWrapperClick.bind(this), false);

        this.dotsWrapper.addEventListener('click', this.onDotsWrapperClick.bind(this), false);

        window.addEventListener('resize', this.onWindowResize.bind(this));
    }

    /**
     * Навешивает события мыши
     */
    bindMouseEvents() {
        this.wrapper.addEventListener('mouseenter', this._onWrapperMouseenter.bind(this));
        this.wrapper.addEventListener('mouseleave', this._hideVisibleAllControls.bind(this));
        this.wrapper.addEventListener('mousemove', this._onWrapperMousemove.bind(this));
    }

    /**
     * События при изменении размера окна
     */
    onWindowResize() {
        this.isTouch = Utils.isTouch();
        this.width = this.wrapper.children[0].offsetWidth;
        this.position = -(this.width * (this.activeSlide - 1));
        this.slideTo(this.position);
        this.setHeight();
        this.positionControls();
        if (!this.isTouch) {
            this.bindMouseEvents();
        }
    }

    /**
     * События при нажатии на точки
     * @param {Object} event - объект события
     */
    onDotsWrapperClick(event) {
        const index = Utils.getElementIndex(event.target) + 1;

        this.activeSlide = index;
        this.dotActivate();
        this.slideTo(this.goTo(index));
    }

    /**
     * События при нажатии обертку слайдера
     * @param {Object} event - объект события
     */
    onWrapperClick(event) {
        if (event.target.closest('a')) {
            return;
        }
        const method = this._isLeftSide(event) ? 'isSlideLeft' : 'isSlideRight';
        this[method]();
    }

    /**
     * События при начала движения мыши
     * @param {Object} event - объект события
     * @private
     */
    _onWrapperMouseenter(event) {
        if (event.target.closest('a')) {
            return;
        }
        this.currentControl = this._isLeftSide(event) ? this.arrowLeft : this.arrowRight;
        this._setVisibleControl(this.currentControl);
    }

    /**
     * События при движении мыши
     * @param {Object} event - объект события
     * @private
     */
    _onWrapperMousemove(event) {
        if (event.target.closest('a')) {
            this._hideVisibleAllControls();
            return;
        } else {
            this._setVisibleControl(this.currentControl);
        }

        if (this._isLeftSide(event) && this._isCurrentControlRight()) {
            this._changeStateControls(this.arrowRight, this.arrowLeft);
        } else if (!this._isLeftSide(event) && !this._isCurrentControlRight()) {
            this._changeStateControls(this.arrowLeft, this.arrowRight);
        }
        this._setCursorControlPosition(event);
    }

    /**
     * Добавляет слайды.
     */
    appendChilds() {
        for (let i = 0; i < this.childrens.length; i++) {
            const item = document.createElement('div');

            item.classList.add('b-slider__item');
            item.appendChild(this.childrens[i]);

            this.wrapper.appendChild(item);
            this.width = this.wrapper.children[0].offsetWidth;
        }
    }

    /**
     * Определяет максимальную высоту слайдов, и устанавливает ее обертке ().
     */
    setHeight() {
        if (!this.autoheight) {
            return false;
        }

        const newArray = this.childrens.map((item) => {
            const image = item.querySelector('img');
            if (image) {
                return image.clientHeight;
            } else {
                return 0;
            }
        });

        const checkWithoutImage = includes(newArray, 0); //метод нативно не поддерживается в IE


        if(!checkWithoutImage) {
            const maxImageHeight = Math.max.apply(null, newArray);
            this.wrapper.style.height = `${maxImageHeight}px`;
        }
    };

    /**
     * Меняет активное значение у точки.
     */
    dotActivate() {
        this.allDots = Array.from(this.gallery.querySelectorAll('.b-slider__dot'));
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
        mediator.publish('sliderReady');
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
                return;//чтото тут не так
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
    isSlideRight(event) {
        if (this.position <= -(this.width * (this.itemsCount - 1))) {

            if (!this.infinite) {
                return;
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

    /**
     * Метод позицианирует стрекли и точки в слайдере в блоке 'medium-info', напротив текста в описании
     */
    positionControls() {
        if  (!this.mediumDescription) {
            return;
        }

        this.galleryHeight =this.gallery.clientHeight;
        this.mediumPictureHeight = this.gallery.querySelector('.b-medium-info-slider__description').clientHeight;

        this.positionSoftZone = 3;

        this.controlsPosition = this.galleryHeight - this.mediumPictureHeight + this.positionSoftZone;

        this.dotsWrapper.style.top = `${this.controlsPosition}px`;
        this.arrowsWrapper.style.top = `${this.controlsPosition}px`;
    }

    /**
     * Проверяет левая сторона ли
     * @param {Object} event - объект события
     * @return {boolean} если true, то левая
     * @private
     */
    _isLeftSide(event) {
        const halfWidthGallery = this.gallery.offsetWidth / 2;
        return event.offsetX < halfWidthGallery;
    }

    /**
     * Проверяет текущий контрол правый
     * @return {boolean} если true, то правый
     * @private
     */
    _isCurrentControlRight() {
        return this.currentControl === this.arrowRight;
    }

    /**
     * Устанавливает текущий контрол
     * @param {HTMLElement} oldControl - старый контрол
     * @param {HTMLElement} newControl - новый контрол
     * @private
     */
    _changeStateControls(oldControl, newControl) {
        this._hideVisibleControl(oldControl);
        this._setVisibleControl(newControl);
        this.currentControl = newControl;
    }

    /**
     * Скрывает все контролы
     * @private
     */
    _hideVisibleAllControls() {
        this.arrows.forEach((arrow) => {
            this._hideVisibleControl(arrow);
        });
    }

    /**
     * Устанавливает видимость контрола
     * @param {HTMLElement} control - контрол
     * @private
     */
    _setVisibleControl(control) {
        if (!control) {
            return;
        }
        control.style.display = 'block';
    }

    /**
     * Скрывает контрол
     * @param {HTMLElement} control - контрол
     * @private
     */
    _hideVisibleControl(control) {
        if (!control) {
            return;
        }
        control.style.display = 'none';
    }

    /**
     * Устанавливает позицию контрола-курсора
     * @param {Object} event - объект события
     * @private
     */
    _setCursorControlPosition(event) {
        if (!this.currentControl) {
            return;
        }
        this.currentControl.style.top = `${event.clientY}px`;
        this.currentControl.style.left = `${event.clientX}px`;
    }
}

export default Slider;
/* eslint-enable */
