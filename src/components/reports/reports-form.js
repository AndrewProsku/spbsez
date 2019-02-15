// import $ from 'jquery';
import Mediator from 'common/scripts/mediator';
import ReportBlock from './report-block';
import templateForm1 from './templates/form-1.twig';
import templateForm2 from './templates/form-2.twig';
import templateForm3 from './templates/form-3.twig';
import templateForm4 from './templates/form-4.twig';
import templateForm5 from './templates/form-5.twig';
import Utils from '../../common/scripts/utils';


const mediator = new Mediator();

class ReportForm {
    constructor() {
        this.target = null;
        this.formsBlockClass = 'j-report-block';
        // Табы
        this.filterClass = 'j-reports-filter';
        this.filterSelectsClass = 'j-reports-select';
        // this.reportsContainerClass = 'j-reports-container';
        this.filterSelectsTitleClass = 'j-reports-select-title';
        this.filterGroupClass = 'j-reports-select-group';
        this.activeGroup = 'b-mini-filter__group_is_active';
        this.filterGroupClass = 'j-reports-select-group';
        this.filterFakeInputClass = 'b-mini-filter__fake';
        this.filterFakeInputSuccessClass = 'b-mini-filter__fake_is_success';
        // this.newsItemClass = 'b-news-item';

        this.forms = [{
            isApproved: false,
            template  : null
        }, {
            isApproved: false,
            template  : null
        }, {
            isApproved: false,
            template  : null
        }, {
            isApproved: false,
            template  : null
        }, {
            isApproved: false,
            template  : null
        }];

        this.unitialForm = 4;

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;

        /**
         * Обработчик для закрытия выпадающих списков с привязкой к this компонента
         * Используется для корректоного удаления обработчика при закрытии тултипа
         */
        this.onClickOutsideBound = this.onClickOutside.bind(this);
    }

    init(options) {
        this.target = options.target;
        const that = this;

        // Табы
        this.filter = document.querySelector(`.${this.filterClass}`);
        this.filterSelect = document.querySelector(`.${this.filterSelectsClass}`);
        this.groups = Array.from(document.querySelectorAll(`.${this.filterGroupClass}`));
        this.filterFakeInputs = Array.from(document.querySelectorAll(`.${this.filterFakeInputClass}`));

        // по умолчанию поля считаются валидными
        // иначе добавляеся поле isInvalid и массив errors
        Utils.send('', '/tests/reports/all.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }
                const forms = response.data.forms;

                forms.forEach((formData, i) => {
                    that.forms[i].template = that.createForm(i);

                    that.initFormBlocks(formData.blocks, that.forms[i].template);
                });

                that.insertForm(that.unitialForm);
            },
            error(error) {
                console.error(error);
            }
        });

        this.bindFilterEvents();
    }

    bindFilterEvents() {
        this.filter.addEventListener('change', (event) => {
            this.changeForm(event.target.id);
            this._setTitlesInSelects();
        });

        this.filterSelect.addEventListener('click', (event) => {
            this._switchSelect(event.target);
        });
    }

    _switchSelect(select) {
        const group = select.closest(`.${this.filterGroupClass}`);

        if (group.classList.contains(this.activeGroup)) {
            group.classList.remove(this.activeGroup);
        } else {
            this._closeAllSelects();
            group.classList.add(this.activeGroup);
        }

        window.addEventListener('click', this.onClickOutsideBound);
    }

    onClickOutside() {
        const group = event.target.closest(`.${this.filterGroupClass}`);

        if (!group) {
            this._closeAllSelects();
        }
    }

    _closeAllSelects() {
        this.groups.forEach((group) => {
            group.classList.remove(this.activeGroup);
        });
        window.removeEventListener('click', this.onClickOutsideBound);
    }

    changeForm(formID) {
        let formNumber = null;

        /* eslint-disable no-magic-numbers */
        switch (formID) {
            case 'form1':
                formNumber = 0;
                break;
            case 'form2':
                formNumber = 1;
                break;
            case 'form3':
                formNumber = 2;
                break;
            case 'form4':
                formNumber = 3;
                break;
            case 'form5':
                formNumber = 4;
                break;
            default: break;
        }
        /* eslint-enable no-magic-numbers */
        this.replaceForm(formNumber);
    }

    _setTitlesInSelects() {
        this.groups.forEach((group) => {
            const select = group.querySelector(`.${this.filterSelectsClass}`);
            const title = group.querySelector(`.${this.filterSelectsTitleClass}`);
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

    replaceForm(formNumber) {
        Utils.clearHtml(this.target);
        this.insertForm(formNumber);
    }

    insertForm(formNumber) {
        Utils.insetContent(this.target, this.forms[formNumber].template);

        // this.initCustomElements();
    }

    // initCustomElements() {
    // Инициализация Select
    // Приходится инициализировать select здесь т.к. компонет похоже не может инциализироваться в компоненте не вставленном
    // на страницу
    // if (this.target.querySelectorAll('.j-reports-form-select').length) {
    //     const select = new Select({
    //         element: '.j-select',
    //
    //         disableSearch: true
    //     });
    //
    //     select.init();
    // }

    // const permissionFileInputs = Array.from(this.target.querySelectorAll('.j-construction-permission-file'));
    //
    // if (permissionFileInputs.length) {
    //     permissionFileInputs.forEach((permissionInput) => {
    //         const fileInput = new InputFile();
    //
    //         fileInput.init({
    //             target: permissionInput
    //         });
    //     });
    // }
    // }

    createForm(formNumber) {
        // Создание шаблона формы
        const template = document.createElement('div');

        /* eslint-disable no-magic-numbers */
        switch (formNumber) {
            case 0:
                template.innerHTML = templateForm1();
                break;
            case 1:
                template.innerHTML = templateForm2();
                break;
            case 2:
                template.innerHTML = templateForm3();
                break;
            case 3:
                template.innerHTML = templateForm4();
                break;
            case 4:
                template.innerHTML = templateForm5();
                break;
            default:
                template.innerHTML = templateForm1();
                break;
        }
        /* eslint-enable no-magic-numbers */

        // Слежение за состояним блоков (заполнен/не заполнен)
        // И изменение кнопок-фильтров
        const formBlocks = Array.from(template.querySelectorAll(`.${this.formsBlockClass}`));

        mediator.subscribe('blockStatusChanged', () => {
            for (let i = 0; i < formBlocks.length; i++) {
                if (formBlocks[i].dataset.approved !== 'true') {
                    this.forms[formNumber].isApproved = false;
                    this.filterFakeInputs[formNumber].classList.remove(this.filterFakeInputSuccessClass);

                    return;
                }
            }
            this.forms[formNumber].isApproved = true;
            this.filterFakeInputs[formNumber].classList.add(this.filterFakeInputSuccessClass);
        });

        return template;
    }

    initFormBlocks(blocksData, formTemplate) {
        const formBlocks = Array.from(formTemplate.querySelectorAll(`.${this.formsBlockClass}`));

        blocksData.forEach((blockData, i) => {
            const reportBlock = new ReportBlock();

            reportBlock.init({
                target: formBlocks[i],
                blockData
            });
        });
    }
}

export default ReportForm;
