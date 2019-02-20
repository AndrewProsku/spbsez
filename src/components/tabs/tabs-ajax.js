import Mediator from 'common/scripts/mediator';
import Utils from 'common/scripts/utils';


class TabsAjax {
    constructor() {
        this.mediator = new Mediator();

        /**
         * Шаблон для контента
         * @type {null}
         */
        this.template = null;

        /**
         * Место куда будет вставлен новый контент
         * @type {null}
         */
        this.content = null;

        /**
         * Массив всех табов
         * @type {Array}
         */
        this.tabs = null;
    }

    init(options) {
        this.target = options.target;
        this.content = options.content;
        this.template = options.template;
        this.moreBtn = document.querySelector('.j-more');
        this.tabs = Array.from(document.querySelectorAll('.j-tabs__item'));

        this._bindClick();
        this._bindChangeOutside();
    }

    _bindClick() {
        this.tabs.forEach((tab) => {
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                this._changeTab(event.target);
            });
        });
    }

    _changeTab(newTab) {
        if (!newTab.classList.contains('is-active')) {
            this._download(`${newTab.dataset.link}?year=${newTab.dataset.year}`, newTab);
        }
    }

    _bindChangeOutside() {
        this.mediator.subscribe('tabSelected', (options) => {
            const newTab = this.target.querySelector(`[data-${options.name}="${options.value}"]`);

            if (newTab) {
                this._changeTab(newTab);
            }
        });
    }

    _download(requestUrl, tabElement) {
        Utils.send({}, requestUrl, {
            success: (response) => {
                Utils.clearHtml(this.content);
                Utils.insetContent(this.content, this.template(response));

                if (this.moreBtn) {
                    this.moreBtn.dataset.year = response.data.YEAR;
                    this.moreBtn.style.display = response.data.IS_END ? 'none' : 'block';
                }

                this._changeActiveTab(tabElement);
                this.mediator.publish('tabLoaded', tabElement);
            }
        });
    }

    _changeActiveTab(currentTab) {
        this.tabs.forEach((tab) => {
            tab.classList.remove('is-active');
        });
        currentTab.classList.add('is-active');
    }
}

export default TabsAjax;
