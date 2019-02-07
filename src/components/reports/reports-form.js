// import $ from 'jquery';
import Mediator from 'common/scripts/mediator';
import ReportBlock from './report-block';
import Utils from '../../common/scripts/utils';

const mediator = new Mediator();

class ReportForm {
    constructor() {
        this.target = null;
        // Табы
        this.filterClass = 'j-reports-filter';
        this.selectsClass = 'j-reports-select';
        // this.reportsContainerClass = 'j-reports-container';
        this.selectsTitleClass = 'j-reports-select-title';
        this.selectGroupClass = 'j-reports-select-group';
        this.activeGroup = 'b-mini-filter__group_is_active';
        // this.newsItemClass = 'b-news-item';

        this.formBlocks = [];
        // возможно флаг не нужен
        this.isFormApproved = false;

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;
    }

    init(options) {
        this.target = options.target;
        this.formBlocks = Array.from(this.target.querySelectorAll('.j-report-block'));
        const that = this;

        // Табы
        this.filter = document.querySelector(`.${this.filterClass}`);
        this.select = document.querySelectorAll(`.${this.selectsClass}`);
        this.groups = Array.from(document.querySelectorAll(`.${this.selectGroupClass}`));


        mediator.subscribe('blockStatusChanged', () => {
            for (let i = 0; i < this.formBlocks.length; i++) {
                if (this.formBlocks[i].dataset.approved !== 'true') {
                    this.isFormApproved = false;
                    this.target.dataset.approved = false;

                    return;
                }
            }
            this.target.dataset.approved = true;
            this.isFormApproved = true;
        });

        // по умолчанию поля считаются валидными
        // иначе добавляеся поле isInvalid и массив errors
        Utils.send('', '/tests/reports/first.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }
                const data = response.data;

                that.initFormBlocks(data.blocks);
            },
            error(error) {
                console.error(error);
            }
        });
    }

    bindEvents() {
        this.filter.addEventListener('change', () => {
            // this._sendFilter();
            this._setTitlesInSelects();
        });

        this.select.addEventListener('click', (event) => {
            this._switchSelect(event.target);
        });

        // window.addEventListener('click', (event) => {
        //     const group = event.target.closest(`.${this.selectGroupClass}`);
        //
        //     if (!group) {
        //         this._closeAllSelects();
        //     }
        // });
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
            const inputsChecked = Array.from(group.querySelectorAll('input:checked'));
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

    initFormBlocks(blocksData) {
        blocksData.forEach((blockData, i) => {
            const reportBlock = new ReportBlock();

            reportBlock.init({
                target    : this.formBlocks[i],
                inputsData: blockData
            });
        });
    }
}

export default ReportForm;
