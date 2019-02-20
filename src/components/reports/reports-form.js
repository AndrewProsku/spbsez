// import $ from 'jquery';
import Mediator from 'common/scripts/mediator';
import ReportBlock from './report-block';
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
        this.addResultClass = 'j-add-result';
        this.submitReportClass = 'j-report-submit';


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

        this.unitialForm = 0;

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
        this.submitReportButton = document.querySelector(`.${this.submitReportClass}`);

        // Табы
        this.filter = document.querySelector(`.${this.filterClass}`);
        this.filterSelect = document.querySelector(`.${this.filterSelectsClass}`);
        this.groups = Array.from(document.querySelectorAll(`.${this.filterGroupClass}`));
        this.filterFakeInputs = Array.from(document.querySelectorAll(`.${this.filterFakeInputClass}`));


        this.getInitialInputsValues();
        this.bindFilterEvents();

        mediator.subscribe('resultBlockDeleted', () => {
            this.resultBlockDeletedHandler();
        });

        this.submitReportButton.addEventListener('click', this.submitReports);
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

    getInitialInputsValues() {
        const that = this;

        Utils.send('', '/tests/reports/all.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }
                const responseForms = response.data.forms;

                responseForms.forEach((formData, i) => {
                    that.forms[i].template = that.createForm(i);

                    if (formData.type && (formData.type === 'results')) {
                        const addResultButton = that.forms[i].template.querySelector(`.${that.addResultClass}`);

                        if (Array.isArray(formData.blocks)) {
                            formData.blocks.forEach((blockData, num) => {
                                const shiftForNumber = 1;
                                const blockNumber = num + shiftForNumber;

                                addResultButton.insertAdjacentHTML('afterend', templateResultBlock({
                                    id    : blockData.ID,
                                    number: blockNumber
                                }));
                            });
                            const formBlocks = that.forms[i].template.querySelectorAll(`.${that.formsBlockClass}`);

                            formData.blocks.reverse().forEach((blockData, blockNumber) => {
                                const reportBlock = new ReportBlock();

                                reportBlock.init({
                                    target: formBlocks[blockNumber],
                                    blockData,
                                    formID: 6
                                });
                            });
                        }
                    } else {
                        that.initFormBlocks(formData.blocks, that.forms[i].template, i);
                    }

                    that.setFormStatus(i);
                });

                mediator.subscribe('blockStatusChanged', (formID) => {
                    that.setFormStatus(formID);
                });

                that.insertForm(that.unitialForm);
            },
            error(error) {
                console.error(error);
            }
        });
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
            case 'form6':
                formNumber = 5;
                break;
            case 'form7':
                formNumber = 6;
                this.forms[6].isVisited = true;
                this.setFormStatus(6);
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
                template.innerHTML = templateForm3();
                break;
            case 3:
                template.innerHTML = templateForm4();
                break;
            case 4:
                template.innerHTML = templateForm5();
                break;
            case 5:
                template.innerHTML = templateForm6();
                break;
            case 6:
                template.innerHTML = templateForm7();
                this.createResultFormTemplate(template);

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
            Utils.send('action=addResult', '/tests/reports/add-result.json', {
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

                    const reportBlock = new ReportBlock();

                    reportBlock.init({
                        target   : addResultButton.nextSibling,
                        blockData: response.data,
                        formID   : 6
                    });
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    setFormStatus(formID) {
        const formBlocks = Array.from(this.forms[formID].template.querySelectorAll(`.${this.formsBlockClass}`));
        const resultsFormID = 6;

        // Если форма с результатаими интеллектурьной деятельности еще не была посещена
        // она не может считаться заполненной
        if (formID === resultsFormID) {
            if (!this.forms[resultsFormID].isVisited) {
                this.forms[formID].isApproved = false;
                this.filterFakeInputs[formID].classList.remove(this.filterFakeInputSuccessClass);
                this.setReportStatus();

                return;
            }
        }

        for (let blockNum = 0; blockNum < formBlocks.length; blockNum++) {
            if (formBlocks[blockNum].dataset.approved !== 'true') {
                this.forms[formID].isApproved = false;
                this.filterFakeInputs[formID].classList.remove(this.filterFakeInputSuccessClass);
                this.setReportStatus();

                return;
            }
        }
        this.forms[formID].isApproved = true;
        this.filterFakeInputs[formID].classList.add(this.filterFakeInputSuccessClass);

        this.setReportStatus();
    }

    setReportStatus() {
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

        Utils.send('action=confirmReport', '/tests/reports/input-update.json', {
            success(response) {
                if (!response.request.status === that.SUCCESS_STATUS) {
                    return true;
                }

                return true;
            },
            error(error) {
                console.error(error);
            }
        });
    }

    initFormBlocks(blocksData, formTemplate, formID) {
        const formBlocks = Array.from(formTemplate.querySelectorAll(`.${this.formsBlockClass}`));

        blocksData.forEach((blockData, i) => {
            const reportBlock = new ReportBlock();

            reportBlock.init({
                target: formBlocks[i],
                blockData,
                formID
            });
        });
    }
}

export default ReportForm;
