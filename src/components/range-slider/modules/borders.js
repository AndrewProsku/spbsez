class Borders {
    /**
     * Метод инициализирует границы для слайдера.
     * @param {IonRangeSlider} slider - экземпляр созданного слайдера.
     */
    init(slider) {
        this.slider = slider;
        this.options = this.slider.input.dataset;
        this.setParams();
        this.createGrayLeftBorder();
        this.createGrayRightBorder();
        this.calcWhiteBorders();

        this.updateServer = false;
    }

    /**
     * Метод устанавливает параметры.
     */
    setParams() {
        this.initial = {
            min : Number(this.options.min),
            max : Number(this.options.max),
            to  : Number(this.options.to),
            from: Number(this.options.from)
        };

        this.border = {
            min: Number(this.options.borderMin),
            max: Number(this.options.borderMax)
        };

        this.length = this.initial.max - this.initial.min;
        this.procent = 100;
    }

    /**
     * Метод создает серую левую полоску. Отображает невыбранные значения, в которых нет допустимых.
     */
    createGrayLeftBorder() {
        let grayPosLeft = 0;
        let grayWidthLeft = 0;

        // Текущая ширина левой серой полоски
        const currentWidthLeft = (this.initial.from - this.initial.min) / this.length * this.procent;

        // Максимальная ширина для левой полоски (далее идет уже значения для темно-зеленой);
        const maxWidthLeft = (this.border.min - this.initial.min) / this.length * this.procent;

        // Проверка на те случаи входит ли выбранное значение в границы допуска.
        if (currentWidthLeft < maxWidthLeft) {
            grayWidthLeft = currentWidthLeft;
        } else {
            grayWidthLeft = maxWidthLeft;
        }

        // Случай когда выбранное значение 'от 'больше чем максимальная граница;
        if (this.initial.from >= this.border.max) {
            grayPosLeft = (this.border.max - this.initial.min) / this.length * this.procent;
            grayWidthLeft = (this.initial.from - this.border.max) / this.length * this.procent;
        }

        const grayLeft = {
            name    : 'grayLeft',
            selector: 'irs-gray-left',
            left    : grayPosLeft,
            width   : grayWidthLeft
        };

        this.createSpan(grayLeft);
    }

    /**
     * Метод создает серую правую полоску. Отображает невыбранные значения, в которых нет допустимых.
     */
    createGrayRightBorder() {
        // Позиция и ширина правого серого куска
        let grayPosRight = 0;
        let grayWidthRight = 0;

        // Текущая ширина правой серой полоски
        const currentPosRight = (this.initial.to - this.initial.min) / this.length * this.procent;

        // Максимальная ширина для правой полоски (далее идет уже значения для темно-зеленой);
        const maxPosRight = (this.border.max - this.initial.min) / this.length * this.procent;

        if (currentPosRight > maxPosRight) {
            grayPosRight = currentPosRight;
        } else {
            grayPosRight = maxPosRight;
        }

        grayWidthRight = this.procent - grayPosRight;

        // Случай когда выбранное значение 'до 'больше чем минимальная граница;
        if (this.initial.to <= this.border.min) {
            grayPosRight = (this.initial.to - this.initial.min) / this.length * this.procent;
            grayWidthRight = ((this.border.min - this.initial.min) / this.length * this.procent) - grayPosRight;
        }

        const grayRight = {
            name    : 'grayRight',
            selector: 'irs-gray-right',
            left    : grayPosRight,
            width   : grayWidthRight
        };

        this.createSpan(grayRight);
    }

    /**
     * Метод создает белую правую и левую полоски, отображают выбранные значения в которых нет никаких вариантов.
     */
    calcWhiteBorders() {
        // Позиция и ширина левого куска
        const posLeft = 0;
        const widthLeft = (this.border.min - this.initial.min) / this.length * this.procent;

        const whiteLeft = {
            name    : 'whiteLeft',
            selector: 'irs-white-left',
            left    : posLeft,
            width   : widthLeft
        };

        // Позиция и ширина правого куска
        const posRight = (this.border.max - this.initial.min) / this.length * this.procent;
        const widthRight = this.procent - posRight;

        const whiteRight = {
            name    : 'whiteRight',
            selector: 'irs-white-right',
            left    : posRight,
            width   : widthRight
        };

        this.createSpan(whiteLeft);
        this.createSpan(whiteRight);
    }

    /**
     * Метод создает границу.
     * @param {object} object - объект с параметрами для спана.
     */
    createSpan(object) {
        const {name, selector, left, width} = object; // eslint-disable-line
        const insert = this.slider.input.previousElementSibling;

        if (this.updateServer) {
            const updateSpan = insert.querySelector(`.${selector}`);

            updateSpan.style.left = `${left}%`;
            updateSpan.style.width = `${width}%`;
        } else {
            const span = document.createElement('span');

            span.classList.add(selector);
            span.style.left = `${left}%`;
            span.style.width = `${width}%`;

            if (!insert.querySelector(`.${selector}`)) {
                insert.appendChild(span);
                this.slider.result[name] = span;
            }
        }
    }

    /* eslint-disable max-statements */

    /**
     * Метод пересчитывает позиции и ширины границ при изменение слайдера
     * @param {IonRangeSlider} target - экземпляр слайдера на котором произошло событие.
     */
    change(target) {
        // Текущие данные с изменяемого слайдера
        const current = {
            from: Number(target.from),
            to  : Number(target.to)
        };

        let grayPosLeft = 0;
        let grayWidthLeft = 0;
        let grayPosRight = 0;
        let grayWidthRight = 0;

        // Проверяем тот случай если 'от' больше чем максимальная граница
        if (current.from >= this.border.max) {
            grayWidthLeft = (current.from - this.border.max) / this.length * this.procent;
            grayPosLeft = (this.border.max - this.initial.min) / this.length * this.procent;
        } else {
            grayWidthLeft = (current.from - this.initial.min) / this.length * this.procent;
        }

        // Проверяем тот случай когда выбранное значение 'до 'больше чем минимальная граница;
        if (current.to <= this.border.min) {
            grayPosRight = (current.to - this.initial.min) / this.length * this.procent;
            grayWidthRight = ((this.border.min - this.initial.min) / this.length * this.procent) - grayPosRight;
        } else {
            grayPosRight = (current.to - this.initial.min) / this.length * this.procent;
            grayWidthRight = this.procent - grayPosRight;
        }

        if (target.grayLeft) {
            // Серая полоска может быть только до минимальной границы
            // Или тогда когда 'от' больше чем максимальная граница
            if ((current.from <= this.border.min) || (current.from >= this.border.max)) {
                target.grayLeft.style.left = `${grayPosLeft}%`;
                target.grayLeft.style.width = `${grayWidthLeft}%`;
            }
        }

        if (target.grayRight) {
            // Серая полоска может быть только до максимальной границы
            // Или тогда когда 'до' меньше чем минимальная граница
            if ((current.to >= this.border.max) || (current.to <= this.border.min)) {
                target.grayRight.style.left = `${grayPosRight}%`;
                target.grayRight.style.width = `${grayWidthRight}%`;
            }
        }

        this.addPsevdoColor(target, current);
    }

    /* eslint-enable max-statements */

    /**
     * Костыль для исправления бага, который возникает если оч быстро двигать ручки и серая граница не успевает заполнится
     * @param {IonRangeSlider} target - экземпляр слайдера на котором произошло событие.
     * @param {object} current - данные мин и макс границы слайдера.
     */
    addPsevdoColor(target, current) {
        // Костыль для исправления бага, который возникает если оч быстро двигать ручки и серая граница не успевает заполнится
        if (current.from > this.border.min) {
            target.whiteLeft.classList.add('irs-pseudo-gray');
        } else {
            target.whiteLeft.classList.remove('irs-pseudo-gray');
        }

        if (current.to < this.border.max) {
            target.whiteRight.classList.add('irs-pseudo-gray');
        } else {
            target.whiteRight.classList.remove('irs-pseudo-gray');
        }
    }

    /**
     * Метод пересчитывает позиции и ширины границ после получение данные с бэкенда.
     * @param {IonRangeSlider} slider - экземпляр слайдера в котором нужно обновить параметры.
     */
    update(slider) {
        this.updateServer = true;
        this.init(slider);
    }
}

export default Borders;

