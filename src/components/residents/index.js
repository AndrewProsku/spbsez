// import $ from 'jquery';
import throttle from 'lodash/throttle';

class Residents {
    constructor() {
        this.$residentsPage = document.querySelector('.j-residents-page');

        // this.$residentsContent = document.querySelector('.j-residents');
        this.residents = Array.from(document.querySelectorAll('.b-resident'));

        this.$filters = document.querySelector('.j-filters');
        // this.filters = Array.from(document.querySelectorAll('.b-filters__item'));

        // Выпадающее меню
        this.$menu = document.querySelector('.j-expanded-menu');
        this.$menuHeader = this.$menu.querySelector('.b-expanded-menu__header');
        this.$menuList = this.$menu.querySelector('.b-expanded-menu__list');
    }

    init() {
        this.$menuHeader.addEventListener('click', () => {
            this.$menu.classList.toggle('b-expanded-menu_is_open');
        });

        this.$menuList.addEventListener('click', (event) => {
            const selectedItem = event.target.closest('.b-expanded-menu__item');
            const selectedCategoryId = selectedItem.dataset.categoryId;
            const categoryName = selectedItem.querySelector('.b-expanded-menu__item-text').textContent;

            this.$residentsPage.dataset.selectedCategoryId = selectedCategoryId;
            // this.$residentsContent.dataset.categoryId = selectedCategoryId; // TODO delete
            this.$menuHeader.textContent = categoryName;
            this.$menu.classList.remove('b-expanded-menu_is_open');
        });

        // Фильтры
        this.$filters.addEventListener('click', (event) => {
            const selectedItem = event.target.closest('.b-filters__item');
            const selectedCategoryId = selectedItem.dataset.categoryId;

            const activeClass = 'b-filters__item_is_active';
            selectedItem.classList.toggle(activeClass);
            this.$residentsPage.dataset.selectedCategoryId = selectedCategoryId;
        });

        // Список резидентов
        this.residents.forEach((resident) => {
            const residentDescription = resident.querySelector('.b-resident__description');

            if (this.isOverflowed(residentDescription)) {
                resident.classList.add('b-resident_is_overflowed');
                resident.addEventListener('mouseenter', this.onResidentMouseEnter);
                resident.addEventListener('mouseleave', this.onResidentMouseLeave);
            }
        });

        const throttleTimeout = 50;

        window.addEventListener('resize', throttle(this.onResize.bind(this), throttleTimeout));
    }

    isOverflowed(element) {
        return element.scrollHeight > element.getBoundingClientRect().height;
    }

    onResidentMouseEnter(event) {
        const resident = event.target;
        const residentWidth = resident.getBoundingClientRect().width;
        const residentHeight = resident.getBoundingClientRect().height;

        resident.style.height = `${residentHeight}px`;
        resident.style.width = `${residentWidth}px`;
        resident.classList.add(`b-resident_is_expanded`);
    }

    onResidentMouseLeave(event) {
        // event.target.style.height = 'auto';
        // document.documentElement.clientWidth;
        // if (document.documentElement.clientWidth < 670) {
        //     event.target.style.height = '420px';
        //     event.target.style.width = 'auto';
        // } else {
        //     event.target.style.height = '480px';
        //     event.target.style.width = '50%';
        // }
        event.target.style.removeProperty('height');
        event.target.style.removeProperty('width');
        event.target.classList.remove(`b-resident_is_expanded`);
    }

    onResize() {
        this.residents.forEach((resident) => {
            const residentDescription = resident.querySelector('.b-resident__description');

            if (this.isOverflowed(residentDescription)) {
                resident.classList.add('b-resident_is_overflowed');

                resident.addEventListener('mouseenter', this.onResidentMouseEnter);
                resident.addEventListener('mouseleave', this.onResidentMouseLeave);
            } else {
                resident.classList.remove('b-resident_is_overflowed');
                resident.removeEventListener('mouseenter', this.onResidentMouseEnter);
                resident.removeEventListener('mouseleave', this.onResidentMouseLeave);
            }
        });
    }
}

export default Residents;
