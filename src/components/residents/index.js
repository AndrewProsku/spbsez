import throttle from 'lodash/throttle';

class Residents {
    constructor() {
        this.$residentsPage = document.querySelector('.j-residents-page');
        this.residents = Array.from(document.querySelectorAll('.b-resident'));
        this.$filters = document.querySelector('.j-filters');

        // Выпадающее меню
        this.$menu = document.querySelector('.j-expanded-menu');
        this.$menuHeader = this.$menu.querySelector('.b-expanded-menu__header');
        this.$itemCounter = this.$menuHeader.querySelector('.b-expanded-menu__item-counter');
        this.$menuList = this.$menu.querySelector('.b-expanded-menu__list');

        this.selectedCategories = new Set();

        this.THROTTLE_TIMEOUT = 50;
    }

    init() {
        this.$menuHeader.addEventListener('click', () => {
            this.$menu.classList.toggle('b-expanded-menu_is_open');
        });

        // Выпадающий список категорий
        this.$menuList.addEventListener('click', (event) => {
            const selectedItem = event.target.closest('.b-expanded-menu__item');
            const selectedCategoryId = selectedItem.dataset.categoryId;

            this.$residentsPage.classList.remove('all-categories');
            this.changeSelectedCategories(selectedCategoryId);
            this.$itemCounter.textContent = this.selectedCategories.size ? String(this.selectedCategories.size) : '';
        });

        // Фильтры-табы
        this.$filters.addEventListener('click', (event) => {
            const selectedItem = event.target.closest('.b-filters__item');
            const selectedCategoryId = selectedItem.dataset.categoryId;

            this.$residentsPage.classList.remove('all-categories');
            this.changeSelectedCategories(selectedCategoryId);
            this.$itemCounter.textContent = this.selectedCategories.size ? String(this.selectedCategories.size) : '';
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

        window.addEventListener('resize', throttle(this.onResize.bind(this), this.THROTTLE_TIMEOUT));
    }

    changeSelectedCategories(categoryId) {
        if (!this.selectedCategories.has(categoryId)) {
            this.selectedCategories.add(categoryId);
            this.$residentsPage.classList.add(`category-${categoryId}`);
        } else if (this.selectedCategories.has(categoryId)) {
            this.selectedCategories.delete(categoryId);
            this.$residentsPage.classList.remove(`category-${categoryId}`);
        }
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
