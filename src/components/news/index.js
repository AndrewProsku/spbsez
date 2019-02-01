import templateNew from './templates/news.twig';
import Utils from '../../common/scripts/utils';

class News {
    constructor() {
        this.filterClass = 'j-news-filter';
        this.selectsClass = 'j-news-select';
        this.newsContainerClass = 'j-news-container';
        this.selectsTitleClass = 'j-news-select-title';
        this.selectGroupClass = 'j-news-select-group';
        this.loadMoreClass = 'j-news-load-more';
        this.activeGroup = 'b-mini-filter__group_is_active';
        this.newsItemClass = 'b-news-item';
        this.contantLoadClass = 'b-news__content_is_load';

        this.formData = new FormData();
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.filter = document.querySelector(`.${this.filterClass}`);
        this.groups = Array.from(document.querySelectorAll(`.${this.selectGroupClass}`));
        this.selects = Array.from(document.querySelectorAll(`.${this.selectsClass}`));
        this.newsContainer = document.querySelector(`.${this.newsContainerClass}`);
        this.loadMore = document.querySelector(`.${this.loadMoreClass}`);
    }

    _bindEvents() {
        this.filter.addEventListener('change', () => {
            this._sendFilter();
            this._setTitlesInSelects();
        });

        this.loadMore.addEventListener('click', (event) => {
            event.preventDefault();

            this._loadMore();
        });

        this.selects.forEach((select) => {
            select.addEventListener('click', () => {
                this._switchSelect(select);
            });
        });

        window.addEventListener('click', (event) => {
            const group = event.target.closest(`.${this.selectGroupClass}`);

            if (!group) {
                this._closeAllSelects();
            }
        });
    }

    _sendFilter() {
        const that = this;
        const dataSend = new FormData(this.filter);

        this.formData = dataSend;
        // this._runLoaderContant();

        Utils.send(dataSend, '/tests/news.json', {
            success(response) {
                const {data} = response;

                that._replaceHtmlNews(data);
            },
            error(error) {
                console.error(error);
            },
            complete() {
                // that._stopLoaderContant();
            }
        }, 'GET');
    }

    _loadMore() {
        const that = this;
        const news = this.newsContainer.querySelectorAll(`.${this.newsItemClass}`);

        this._disableButton();

        if (news.length) {
            this.formData.set('countShowNews', `${news.length}`);
        }

        Utils.send(this.formData, '/tests/news.json', {
            success(response) {
                const {data} = response;

                that._insertHtmlNews(data);
            },
            error(error) {
                console.error(error);
            },
            complete() {
                that._enableButton();
            }
        }, 'GET');
    }

    _switchSelect(select) {
        const group = select.closest(`.${this.selectGroupClass}`);

        if (group.classList.contains(this.activeGroup)) {
            group.classList.remove(this.activeGroup);
        } else {
            this._closeAllSelects();
            group.classList.add(this.activeGroup);
        }
    }

    _closeAllSelects() {
        this.groups.forEach((group) => {
            group.classList.remove(this.activeGroup);
        });
    }

    _setTitlesInSelects() {
        this.groups.forEach((group) => {
            const select = group.querySelector(`.${this.selectsClass}`);
            const title = group.querySelector(`.${this.selectsTitleClass}`);
            const inputsChecked = group.querySelectorAll('input:checked');
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
                titleText = select.dataset.titleDefault;
            }

            Utils.clearHtml(title);
            Utils.insetContent(title, titleText);
        });
    }

    _replaceHtmlNews(data) {
        Utils.clearHtml(this.newsContainer);
        this._insertHtmlNews(data);
    }

    _insertHtmlNews(data) {
        Utils.insetContent(this.newsContainer, templateNew(data));
    }

    _disableButton() {
        this.loadMore.classList.add('is-disabled');
    }

    _enableButton() {
        this.loadMore.classList.remove('is-disabled');
    }

    _runLoaderContant() {
        this.newsContainer.classList.add(this.contantLoadClass);
    }

    _stopLoaderContant() {
        this.newsContainer.classList.remove(this.contantLoadClass);
    }
}

export default News;

