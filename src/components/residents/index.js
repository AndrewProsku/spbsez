import throttle from 'lodash/throttle';
import Utils from '../../common/scripts/utils';

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

    /* eslint-disable */
    // временно

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
            }

            resident.addEventListener('mouseenter', () => {
                if (Utils.isMobile()) {
                    return;
                }

                if (!resident.classList.contains('b-resident_is_overflowed')) {
                    return;
                }

                this.onResidentMouseEnter(resident);
            });

            resident.addEventListener('mouseleave', () => {
                if (Utils.isMobile()) {
                    return;
                }

                if (!resident.classList.contains('b-resident_is_overflowed')) {
                    return;
                }

                this.onResidentMouseLeave(resident);
            });

            resident.addEventListener('click', (event) => {
                if (event.target.tagName === 'A') {
                    return;
                }

                if (!Utils.isMobile()) {
                    return;
                }

                if (!resident.classList.contains('b-resident_is_overflowed')) {
                    return;
                }

                if (resident.classList.contains('b-resident_is_expanded')) {
                    this.onResidentMouseLeave(resident);
                } else {
                    this.onResidentMouseLeaveAll();
                    this.onResidentMouseEnter(resident);
                }
            });
        });

        window.onresize = () => {
            this.onResidentMouseLeaveAll();
        };

        window.addEventListener('resize', throttle(this.onResize.bind(this), this.THROTTLE_TIMEOUT));

        Utils.clickOutside('.b-resident', () => {
            if (!Utils.isMobile()) {
                return;
            }

            this.onResidentMouseLeaveAll();
        }, (target) => {
            if (!target.classList.contains('b-resident_is_overflowed')) {
                this.onResidentMouseLeaveAll();
            }
        });
    }

    /* eslint-enable */

    changeSelectedCategories(categoryId) {
        if (!this.selectedCategories.has(categoryId)) {
            this.selectedCategories.add(categoryId);
            this.$residentsPage.classList.add(`category-${categoryId}`);
        } else if (this.selectedCategories.has(categoryId)) {
            this.selectedCategories.delete(categoryId);
            this.$residentsPage.classList.remove(`category-${categoryId}`);

            this._checkActiveTabs();
        }
    }

    isOverflowed(element) {
        return element.scrollHeight > (element.getBoundingClientRect().height + 5);
    }

    onResidentMouseEnter(resident) {
        if (!resident) {
            return;
        }

        const residentWidth = resident.getBoundingClientRect().width;
        const residentHeight = resident.getBoundingClientRect().height;

        resident.style.height = `${residentHeight}px`;
        resident.style.width = `${residentWidth}px`;
        resident.classList.add(`b-resident_is_expanded`);
    }

    onResidentMouseLeave(resident) {
        if (!resident) {
            return;
        }

        resident.style.removeProperty('height');
        resident.style.removeProperty('width');
        resident.classList.remove(`b-resident_is_expanded`);
    }

    onResidentMouseLeaveAll() {
        this.residents.forEach((resident) => {
            this.onResidentMouseLeave(resident);
        });
    }

    onResize() {
        this.residents.forEach((resident) => {
            const residentDescription = resident.querySelector('.b-resident__description');

            if (this.isOverflowed(residentDescription)) {
                resident.classList.add('b-resident_is_overflowed');
            } else {
                resident.classList.remove('b-resident_is_overflowed');
            }
        });
    }

    // Если нет активных табов то показываем все
    _checkActiveTabs() {
        const classResidentsPage = Array.from(this.$residentsPage.classList).join(', ');
        const notSearch = -1;

        if (!classResidentsPage.length) {
            return;
        }

        if (classResidentsPage.search('category-') !== notSearch) {
            return;
        }

        this.$residentsPage.classList.add('all-categories');
    }
}

export default Residents;
