import templateDocuments from './templates/documents.twig';
import templateProcurements from './templates/procurements.twig';
import Utils from '../../common/scripts/utils';

class Disclosure {
    constructor() {
        this.filterClass = 'j-disclosure-filter';
        this.selectsClass = 'j-disclosure-select';
        this.contentClass = 'j-disclosure-content';
        this.selectsTitleClass = 'j-disclosure-select-title';
        this.selectGroupClass = 'j-disclosure-select-group';
        this.activeGroup = 'b-mini-filter__group_is_active';
        this.loadMoreClass = 'j-disclosure-load-more';
        this.contentItemClass = 'j-disclosure-content-item';

        this.SUCCESS_STATUS = 1;

        /**
         * Обработчик для закрытия выпадающих списков с привязкой к this компонента
         * Используется для корректоного удаления обработчика при закрытии списка
         */
        this.onClickOutsideBound = this._onClickOutside.bind(this);
    }

    init(options) {
        this.target = options.target;
        this.filter = this.target.querySelector(`.${this.filterClass}`);
        this.group = this.target.querySelector(`.${this.selectGroupClass}`);
        this.select = this.filter.querySelector(`.${this.selectsClass}`);
        this.selectTitle = this.select.querySelector(`.${this.selectsTitleClass}`);
        this.content = this.target.querySelector(`.${this.contentClass}`);
        this.loadMore = this.target.querySelector(`.${this.loadMoreClass}`);
        this.isProcurement = this.target.classList.contains('j-disclosure-procurement');

        this._bindEvents();
    }

    _bindEvents() {
        this.filter.addEventListener('change', () => {
            this._sendFilter();
            this._setTitlesInSelects();
        });

        this.select.addEventListener('click', () => {
            this._switchSelect();
        });

        if (this.loadMore) {
            this.loadMore.addEventListener('click', (event) => {
                event.preventDefault();

                this._loadMore();
            });
        }

        window.addEventListener('click', this.onClickOutsideBound);
    }

    _loadMore() {
        const that = this;
        const items = this.content.querySelectorAll(`.${this.contentItemClass}`);
        const formData = new FormData(this.filter);

        this.loadMore.classList.add('is-disabled');

        if (items.length) {
            formData.append('showed', `${items.length}`);
        }

        if (this.loadMore.dataset.ajaxParam) {
            formData.append(`${this.loadMore.dataset.ajaxParam}`, `${this.loadMore.dataset.ajaxParamValue}`);
        }

        Utils.send(formData, this.loadMore.dataset.send, {
            success(response) {
                if (response.request.status === that.SUCCESS_STATUS) {
                    const {data} = response;

                    that._insertHtmlContent(data);

                    if (!data.showMore) {
                        Utils.hide(that.loadMore);
                    }
                }
            },
            error(error) {
                console.error(error);
            },
            complete() {
                that.loadMore.classList.remove('is-disabled');
            }
        }, 'post');
    }

    _onClickOutside(event) {
        const group = event.target.closest(`.${this.selectGroupClass}`);

        if (!group) {
            this._closeAllSelects();
        }
    }

    _sendFilter() {
        const that = this;
        const formData = new FormData(this.filter);

        Utils.send(formData, this.filter.dataset.send, {
            success(response) {
                if (response.request.status === that.SUCCESS_STATUS) {
                    const {data} = response;

                    that._replaceHtmlContent(data);

                    if (!that.loadMore) {
                        return;
                    }

                    if (!data.showMore) {
                        Utils.hide(that.loadMore);

                        return;
                    }

                    Utils.show(that.loadMore);
                }
            },
            error(error) {
                console.error(error);
            }
        }, this.filter.getAttribute('method') || 'post');
    }

    _switchSelect() {
        if (this.group.classList.contains(this.activeGroup)) {
            this.group.classList.remove(this.activeGroup);
        } else {
            this._closeAllSelects();
            this.group.classList.add(this.activeGroup);
        }

        window.addEventListener('click', this.onClickOutsideBound);
    }

    _closeAllSelects() {
        this.group.classList.remove(this.activeGroup);
        window.removeEventListener('click', this.onClickOutsideBound);
    }

    _setTitlesInSelects() {
        const inputsChecked = Array.from(this.group.querySelectorAll('input:checked'));
        let titleText = null;

        inputsChecked.forEach((input) => {
            const text = input.dataset.text;

            if (!text) {
                return;
            }

            if (titleText) {
                titleText = `${titleText}, ${text}`;
            } else {
                titleText = text;
            }
        });

        if (!titleText) {
            titleText = this.select.dataset.titleDefault;
        }

        Utils.clearHtml(this.selectTitle);
        Utils.insetContent(this.selectTitle, titleText);
    }

    _replaceHtmlContent(data) {
        Utils.clearHtml(this.content);
        this._insertHtmlContent(data);
    }

    _insertHtmlContent(data) {
        const template = this.isProcurement ? templateProcurements(data) : templateDocuments(data);

        Utils.insetContent(this.content, template);
    }
}

export default Disclosure;

