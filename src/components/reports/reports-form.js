import Mediator from 'common/scripts/mediator';
import ReportBlock from './report-block';
import templateComments from './templates/comments.twig';
import templateForm1 from './templates/form-1.twig';
import templateForm2 from './templates/form-2.twig';
import templateForm3 from './templates/form-3.twig';
import templateForm4 from './templates/form-4.twig';
import templateForm5 from './templates/form-5.twig';
import templateForm6 from './templates/form-6.twig';
import templateForm7 from './templates/form-7.twig';
import templateResultBlock from './templates/result-block.twig';
import Utils from '../../common/scripts/utils';


const mediator = new Mediator();

class ReportForm {
    constructor() {
        this.target = null;
        this.formsBlockClass = 'j-report-block';
        this.reportId = 0;
        this.baseUrl = '/api/report/';

        // Табы
        this.filterClass = 'j-reports-filter';
        this.filterSelectsClass = 'j-reports-select';
        this.filterSelectsTitleClass = 'j-reports-select-title';
        this.filterGroupClass = 'j-reports-select-group';
        this.activeGroup = 'b-mini-filter__group_is_active';
        this.filterFakeInputClass = 'b-mini-filter__fake';
        this.filterFakeInputSuccessClass = 'b-mini-filter__fake_is_success';
        this.filterFakeInputErrorClass = 'b-mini-filter__fake_is_error';

        this.addResultClass = 'j-add-result';
        this.resultsContainerClass = 'j-report-results';
        this.submitReportClass = 'j-report-submit';
        this.approveReportClass = 'j-report-approve';
        this.commentsBlockClass = 'b-report-comments';

        this.residentNameField = document.querySelector('.j-report-resident-name input');
        this.oezNameField = document.querySelector('.j-report-oez-name input');


        // this.isReportFulfilled = false;
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
        }, {
            isApproved: false,
            template  : null
        }, {
            isVisited : false,
            isApproved: false,
            template  : null
        }];

        this.initialForm = 0;

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;

        /**
         * Обработчик для закрытия выпадающих списков с привязкой к this компонента
         * Используется для корректоного удаления обработчика при закрытии списка
         */
        this.onClickOutsideBound = this.onClickOutside.bind(this);
    }

    init(options) {
        this.target = options.target;
        this.submitReportButton = document.querySelector(`.${this.submitReportClass}`);
        this.reportId = parseInt(this.target.dataset.reportId, 10);
        this.baseUrl = options.baseUrl || this.baseUrl;

        // Табы
        this.filters = Array.from(document.querySelectorAll(`.${this.filterClass}`));
        this.groups = Array.from(document.querySelectorAll(`.${this.filterGroupClass}`));
        this.filterFakeInputs = [];

        if (Utils.keyExist(this.target.dataset, 'readOnly')) {
            this.type = 'readonly';
            this.residentNameField.disabled = true;
            this.oezNameField.disabled = true;
        }

        this.getInitialInputsValues();

        this.filters.forEach((filter) => {
            this.filterFakeInputs.push(Array.from(filter.querySelectorAll(`.${this.filterFakeInputClass}`)));
            this.bindFilterEvents(filter);
        });

        mediator.subscribe('resultBlockDeleted', () => {
            this.resultBlockDeletedHandler();
        });

        if (this.submitReportButton) {
            const that = this;

            this.submitReportButton.addEventListener('click', () => {
                this.submitReports(that);
            });
        }
    }

    initCommentsBlock(formID) {
        const formBlocks = Array.from(this.forms[formID].template.querySelectorAll(`.${this.formsBlockClass}`));
        const errorBlocks = [];

        formBlocks.forEach((block) => {
            if (Utils.keyExist(block.dataset, 'hasError')) {
                errorBlocks.push(block.querySelector('.b-report-block__header').dataset.number);
            }
        });

        if (document.querySelector(`.${this.commentsBlockClass}`)) {
            Utils.removeElement(document.querySelector(`.${this.commentsBlockClass}`));
        }

        if (errorBlocks.length) {
            document.querySelector(`.${this.filterClass}`).insertAdjacentHTML('afterend',
                templateComments({
                    blocks: errorBlocks
                }));
        }
    }

    getInitialInputsValues() {
        const that = this;

        this.residentNameField.addEventListener('change', (event) => {
            this.sendNewValues(event.target);
        });
        this.oezNameField.addEventListener('change', (event) => {
            this.sendNewValues(event.target);
        });

        Utils.send(`a=get&id=${that.reportId}`, that.baseUrl, {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }
                const responseForms = response.data.forms;

                if (response.data.NAME) {
                    that.residentNameField.value = response.data.NAME;
                }
                if (response.data.NAME_SEZ) {
                    that.oezNameField.value = response.data.NAME_SEZ;
                }

                if (!responseForms.length) {
                    return;
                }

                responseForms.forEach((formData, i) => {
                    that.forms[i].template = that.createForm(i);

                    if (formData.type && formData.type === 'results') {
                        that.initResultsForm(formData, i);
                    } else {
                        that.initFormBlocks(formData.blocks, that.forms[i].template, i);
                    }

                    that.setFormStatus(i);
                });

                mediator.subscribe('blockStatusChanged', (formID) => {
                    that.setFormStatus(formID);
                    that.initCommentsBlock(formID);
                });

                that.insertForm(that.initialForm);
                const formBlocks = that.forms[that.initialForm].template.querySelectorAll(`.${that.formsBlockClass}`);

                that.toggleApproveFormButton(formBlocks, that.initialForm);
            },
            error(error) {
                console.error(error);
            }
        });
    }

    initResultsForm(data, formID) {
        const resultsContainer = this.forms[formID].template.querySelector(`.${this.resultsContainerClass}`);
        const isReadonly = this.type === 'readonly';
        const that = this;

        if (Array.isArray(data.blocks)) {
            data.blocks.forEach((blockData, num) => {
                const shiftForNumber = 1;
                const blockNumber = num + shiftForNumber;

                resultsContainer.insertAdjacentHTML('afterBegin', templateResultBlock({
                    id    : blockData.ID,
                    number: blockNumber,
                    isReadonly
                }));
            });
            const formBlocks = this.forms[formID].template.querySelectorAll(`.${this.formsBlockClass}`);

            data.blocks.reverse().forEach((blockData, blockNumber) => {
                (new ReportBlock()).init({
                    reportId: that.reportId,
                    target  : formBlocks[blockNumber],
                    blockData,
                    formID,
                    isReadonly
                });
            });
        }
    }

    sendNewValues(input) {
        const that = this;

        Utils.send(`a=update&id=${this.reportId}&field=${input.id}&val=${input.value}`, that.baseUrl, {
            success(response) {
                if (response.request.status === that.SUCCESS_STATUS) {
                    that.toggleSubmitButton();
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    bindFilterEvents(filter) {
        const filterSelect = filter.querySelector(`.${this.filterSelectsClass}`);

        filter.addEventListener('change', (event) => {
            this.filters.forEach((reportFilter) => {
                reportFilter.querySelector(`input[value="${event.target.value}"]`).checked = true;
            });
            this.changeForm(event.target.value);
            this._setTitlesInSelects();
        });

        filterSelect.addEventListener('click', (event) => {
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
        let formNumber = parseInt(formID.replace('form', ''), 10);

        formNumber--;

        if (formNumber < 0) {
            return;
        }

        if (formNumber === 6) {
            this.forms[formNumber].isVisited = true;
            this.setFormStatus(formNumber);
        }

        this.replaceForm(formNumber);

        const formBlocks = Array.from(this.forms[formNumber].template.querySelectorAll(`.${this.formsBlockClass}`));

        this.toggleApproveFormButton(formBlocks, formNumber);
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
        this.initCommentsBlock(formNumber);

        Utils.insetContent(this.target, this.forms[formNumber].template);
        this.target.dataset.currentForm = formNumber;
    }

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
                template.innerHTML = templateForm3({
                    readonly: this.type === 'readonly'
                });
                break;
            case 3:
                template.innerHTML = templateForm4({
                    readonly: this.type === 'readonly'
                });
                break;
            case 4:
                template.innerHTML = templateForm5({
                    readonly: this.type === 'readonly'
                });
                break;
            case 5:
                template.innerHTML = templateForm6();
                break;
            case 6:
                template.innerHTML = templateForm7({
                    readonly: this.type === 'readonly'
                });
                if (this.type !== 'readonly') {
                    this.createResultFormTemplate(template);
                }
                break;
            default:
                template.innerHTML = templateForm1();
                break;
        }

        /* eslint-enable no-magic-numbers */
        return template;
    }

    createResultFormTemplate(template) {
        const that = this;
        const addResultButton = template.querySelector(`.${this.addResultClass}`);

        addResultButton.addEventListener('click', () => {
            Utils.send(`a=addGroup&id=${that.reportId}&type=results&formNum=6`, that.baseUrl, {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const blocksAmount = template.querySelectorAll(`.${that.formsBlockClass}`).length;
                    const shiftForNumber = 1;
                    const blockNumber = blocksAmount + shiftForNumber;

                    addResultButton.insertAdjacentHTML('afterend', templateResultBlock({
                        id    : response.data.ID,
                        number: blockNumber
                    }));

                    (new ReportBlock()).init({
                        target    : addResultButton.nextSibling,
                        blockData : response.data,
                        formID    : 6,
                        isReadonly: that.type === 'readonly',
                        reportId  : that.reportId,
                        baseUrl   : that.baseUrl
                    });
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    resultBlockDeletedHandler() {
        const resultsForm = 6;
        const resultsBlocks = this.forms[resultsForm].template.querySelectorAll(`.${this.formsBlockClass}`);
        let resultBlocksCounter = resultsBlocks.length;

        Array.from(resultsBlocks).forEach((block) => {
            block.querySelector('.b-report-block__header').dataset.number = resultBlocksCounter;
            // eslint-disable-next-line no-magic-numbers
            resultBlocksCounter -= 1;
        });
    }

    toggleApproveFormButton(formBlocks, formNumber) {
        const that = this;

        for (let blockNum = 0; blockNum < formBlocks.length; blockNum++) {
            if (Utils.keyExist(formBlocks[blockNum].dataset, 'prefilled')) {
                if (document.querySelector(`.${that.approveReportClass}`)) {
                    const approveButton = document.querySelector(`.${that.approveReportClass}`);

                    approveButton.setAttribute('data-form-id', formNumber);
                    approveButton.onclick = this.onApproveButtonClick.bind(this, formBlocks, formNumber);
                } else {
                    const approveFormButton = document.createElement('button');

                    approveFormButton.classList.add('button', 'button_icon_check');
                    approveFormButton.classList.add('b-report-approve', `${that.approveReportClass}`);
                    approveFormButton.setAttribute('type', 'button');
                    approveFormButton.setAttribute('data-form-id', formNumber);
                    approveFormButton.innerHTML = 'Подтвердить данные формы';
                    approveFormButton.onclick = this.onApproveButtonClick.bind(this, formBlocks, formNumber);

                    this.target.parentNode.insertBefore(approveFormButton, this.target);
                }
                break;
            }
            // eslint-disable-next-line no-magic-numbers
            if ((blockNum === formBlocks.length - 1) &&
                document.querySelector(`.${that.approveReportClass}`)) {
                Utils.removeElement(document.querySelector(`.${that.approveReportClass}`));
            }
        }
    }

    onApproveButtonClick(formBlocks, formNumber) {
        const inputs = [];

        formBlocks.forEach((el) => {
            if (!Utils.keyExist(el.dataset, 'prefilled')) {
                return;
            }

            el.querySelectorAll('input[data-prefilled]').forEach((inp) => {
                inputs.push(inp.name);
            });
        });

        Utils.send(`a=confirmForm&id=${this.reportId}&fields=${JSON.stringify(inputs)}`, this.baseUrl, {
            success(response) {
                if (response.request.status === this.SUCCESS_STATUS) {
                    mediator.publish('formApproved', Number(formNumber));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    /* eslint-disable max-lines-per-function, max-statements */
    setFormStatus(formID) {
        const formBlocks = Array.from(this.forms[formID].template.querySelectorAll(`.${this.formsBlockClass}`));
        const resultsFormID = 6;
        const arrayLengthShift = 1;
        let hasEmpty = false;
        let hasError = false;
        let hasPrefilled = false;

        for (let blockNum = 0; blockNum < formBlocks.length; blockNum++) {
            let blockStatus = '';

            if (Utils.keyExist(formBlocks[blockNum].dataset, 'prefilled')) {
                blockStatus = 'prefilled';
            } else if (Utils.keyExist(formBlocks[blockNum].dataset, 'hasError')) {
                blockStatus = 'hasError';
            } else if (Utils.keyExist(formBlocks[blockNum].dataset, 'approved')) {
                blockStatus = 'approved';
            }

            switch (blockStatus) {
                case 'hasError': {
                    if (hasError) {
                        break;
                    }

                    this.forms[formID].isApproved = false;
                    this.filters.forEach((filter, i) => {
                        this.filterFakeInputs[i][formID].classList.remove(this.filterFakeInputSuccessClass);
                        this.filterFakeInputs[i][formID].classList.add(this.filterFakeInputErrorClass);
                    });
                    hasError = true;
                    break;
                }
                case 'prefilled': {
                    if (hasPrefilled) {
                        break;
                    }

                    this.forms[formID].isApproved = false;
                    this.filters.forEach((filter, i) => {
                        this.filterFakeInputs[i][formID].classList.remove(this.filterFakeInputSuccessClass);
                    });

                    hasPrefilled = true;
                    break;
                }
                case 'approved': {
                    break;
                }
                default: {
                    if (hasEmpty) {
                        break;
                    }

                    this.forms[formID].isApproved = false;
                    this.filters.forEach((filter, i) => {
                        this.filterFakeInputs[i][formID].classList.remove(this.filterFakeInputSuccessClass);
                    });
                    hasEmpty = true;
                    break;
                }
            }

            if (blockNum === (formBlocks.length - arrayLengthShift)) {
                // Если форма с результатаими интеллектурьной деятельности еще не была посещена
                // она не может считаться заполненной
                if ((formID === resultsFormID) && !this.forms[resultsFormID].isVisited) {
                    this.forms[formID].isApproved = false;
                    this.filters.forEach((filter, i) => {
                        this.filterFakeInputs[i][formID].classList.remove(this.filterFakeInputSuccessClass);
                    });
                } else if (!hasError) {
                    this.filters.forEach((filter, i) => {
                        this.filterFakeInputs[i][formID].classList.remove(this.filterFakeInputErrorClass);
                    });
                    if (document.querySelector(`.${this.commentsBlockClass}`)) {
                        Utils.removeElement(document.querySelector(`.${this.commentsBlockClass}`));
                    }

                    if (!hasEmpty && !hasPrefilled) {
                        this.forms[formID].isApproved = true;

                        this.filters.forEach((filter, i) => {
                            this.filterFakeInputs[i][formID].classList.add(this.filterFakeInputSuccessClass);
                        });
                    }
                }
                this.toggleSubmitButton();
            }
        }
    }
    /* eslint-enable max-lines-per-function, max-statements */

    /**
     * Включает/отключает кнопку отправки отчета
     */
    toggleSubmitButton() {
        if (!this.submitReportButton) {
            return;
        }

        if (!this.residentNameField.value || !this.oezNameField.value || this.type === 'readonly') {
            this.submitReportButton.disabled = true;

            return;
        }

        for (let i = 0; i < this.forms.length; i++) {
            if (!this.forms[i].isApproved) {
                this.submitReportButton.disabled = true;

                return;
            }
        }

        this.submitReportButton.disabled = false;
    }

    submitReports() {
        const that = this;

        Utils.send(`a=confirm&id=${that.reportId}`, that.baseUrl, {
            success(response) {
                if (!response.request.status === that.SUCCESS_STATUS) {
                    return true;
                }

                window.location = response.data.backUrl;

                return true;
            },
            error(error) {
                console.error(error);
            }
        });
    }

    initFormBlocks(blocksData, formTemplate, formID) {
        const formBlocks = Array.from(formTemplate.querySelectorAll(`.${this.formsBlockClass}`));
        const isReadonly = this.type === 'readonly';
        const that = this;

        blocksData.forEach((blockData, i) => {
            (new ReportBlock()).init({
                reportId: that.reportId,
                baseUrl : that.baseUrl,
                target  : formBlocks[i],
                blockData,
                formID,
                isReadonly
            });
        });
    }
}

export default ReportForm;
