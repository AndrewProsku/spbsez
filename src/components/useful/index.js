import templateItem from './templates/item.twig';
import Utils from '../../common/scripts/utils';

class Useful {
    constructor() {
        this.containerClass = 'j-useful-content';
        this.mainClass = 'j-useful-main';
        this.loadClass = 'j-useful-load-more';
        this.itemClass = 'j-useful-item';
    }

    init() {
        this._initElements();
        this._bindEvents();
    }

    _initElements() {
        this.main = document.querySelector(`.${this.mainClass}`);
        this.container = document.querySelector(`.${this.containerClass}`);
        this.load = document.querySelector(`.${this.loadClass}`);
    }

    _bindEvents() {
        this.load.addEventListener('click', (event) => {
            event.preventDefault();

            this._loadMore();
        });
    }

    _loadMore() {
        const that = this;
        const items = this.container.querySelectorAll(`.${this.itemClass}`);
        const formData = new FormData();

        this._disableButton();

        if (items.length) {
            formData.append('showed', `${items.length}`);
        }

        Utils.send(formData, this.main.dataset.action, {
            success(response) {
                const {data} = response;

                that._insertHtmlNews(data);

                if (!data.showMore) {
                    that._hideLoadMore();
                }
            },
            error(error) {
                console.error(error);
            },
            complete() {
                that._enableButton();
            }
        }, this.main.dataset.method);
    }

    _insertHtmlNews(data) {
        Utils.insetContent(this.container, templateItem(data));
    }

    _disableButton() {
        this.load.classList.add('is-disabled');
    }

    _enableButton() {
        this.load.classList.remove('is-disabled');
    }

    _hideLoadMore() {
        Utils.hide(this.load);
    }
}

export default Useful;

