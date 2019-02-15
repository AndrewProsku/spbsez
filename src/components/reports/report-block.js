import $ from 'jquery';
import InputFile from 'components/forms/file';
import Mediator from 'common/scripts/mediator';
import Select from 'components/forms/select';
import templateExportForm from './templates/export-countries.twig';
import templatePermissionForm from './templates/permission-form.twig';
import templateStageForm from './templates/stage-block.twig';
import templateTooltip from 'components/tooltip/custom-tooltip.twig';
import Tooltip from 'components/tooltip/';
import Utils from '../../common/scripts/utils';

const mediator = new Mediator();

class ReportBlock {
    constructor() {
        this.target = null;
        this.inputs = null;

        this.approveClass = 'b-report-block_status_approved';
        this.rejectClass = 'b-report-block_status_rejected';
        this.untouchedIputClass = 'b-input-block_is_untouched';
        this.stageBlockClass = 'j-report-stage-block';
        this.stageSelectClass = 'j-reports-form-select';
        this.permissionFormClass = 'j-permission-form';
        this.stageAddButtonClass = 'j-report-stage-add';
        this.stageDeleteButtonClass = 'j-delete-stage';
        this.fileInputClass = 'j-file-input-block';

        /**
         * Данные о состоянии инпутов блока, полученные от сервера
         */
        // this.blockData = {};
        this.inputsData = {};
        this.inputsStatus = {};

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;
    }

    init(options) {
        this.target = options.target;
        // this.blockData = options.blockData;
        const blockData = options.blockData;

        // Инициализация блока со стадиями строительства
        if (blockData.type) {
            switch (blockData.type) {
                // Инициализация блока со стадиями строительства
                case 'construction-stage': {
                    this.inputsData.fields = [];
                    blockData.stages.forEach((stage) => {
                        this.inputsData.fields.push(stage.fields);
                        let hasExtraForm = false;

                        stage.fields.forEach((field) => {
                            this.inputsData.fields.push(field);
                            // eslint-disable-next-line no-magic-numbers
                            if (field.id.indexOf('construction-stage') !== -1) {
                                hasExtraForm = field.value === 'stage4' || field.value === 'stage6';
                            }
                        });
                        // eslint-disable-next-line no-magic-numbers
                        const isStagesDeletable = blockData.stages.length > 1;

                        this.insertStageForm(stage.stageID, hasExtraForm, isStagesDeletable);
                    });

                    // Добавление новой стадии строительства
                    this.addStage();
                    break;
                }
                case 'export-countries': {
                    this.inputsData.fields = [];
                    blockData.groups.forEach((group) => {
                        this.inputsData.fields.push(group.fields);
                        group.fields.forEach((field) => {
                            this.inputsData.fields.push(field);
                        });
                        // eslint-disable-next-line no-magic-numbers
                        const isGroupDeletable = blockData.groups.length > 1;

                        this.insertExportGroupForm(group.groupID, isGroupDeletable);
                    });

                    // Добавление новой стадии строительства
                    this.addExportGroup();
                    break;
                }
                default:
                    this.inputsData = blockData;
                    break;
            }
        } else {
            this.inputsData = blockData;
        }

        this.inputs = Array.from(this.target.querySelectorAll('input, select'));
        this._getInputsValues();
        this._bindInputsEvents(this.inputs);

        // Инициализация тултипов
        this.initTooltips();
    }

    initSelect(input) {
        new Select({
            element: $(input),

            disableSearch: true
        }).init();
        // eslint-disable-next-line no-magic-numbers
        if (input.id.indexOf('construction-stage') !== -1) {
            input.onchange = this.stageSelectHandler.bind(this);
        }
    }

    initTooltips() {
        const helpTooltips = Array.from(this.target.querySelectorAll('.j-help'));

        if (helpTooltips.length) {
            helpTooltips.forEach((helpTooltip) => {
                const tooltip = new Tooltip();

                tooltip.init({
                    target  : helpTooltip,
                    template: templateTooltip
                });
            });
        }
    }

    /**
     * Инициализация кастомных инпутов
     * Отправка значений инпутов при их изменении
     * @param {Array} inputs - массив со всеми инпутами блока
     */
    _bindInputsEvents(inputs) {
        inputs.forEach((input) => {
            switch (input.type) {
                case 'text':
                case 'email':
                case 'tel': {
                    input.addEventListener('focus', (event) => {
                        event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
                    });
                    input.addEventListener('change', (event) => {
                        this.sendNewValue(event.target);
                    });
                    break;
                }
                case 'file': {
                    // Инициализация компонента
                    const that = this;
                    const fileInputBlock = input.closest('.b-input-block');
                    const fileInput = new InputFile();

                    fileInput.init({
                        target: fileInputBlock
                    });

                    input.addEventListener('change', (event) => {
                        event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
                        const formData = new FormData(event.target.closest('form'));

                        Utils.send(formData, '/tests/reports/input-update.json', {
                            // success(response) {
                            //     if (!response.request.status === that.SUCCESS_STATUS) {}
                            // },
                            error(error) {
                                console.error(error);
                            }
                        });
                    });

                    fileInputBlock.querySelector('.b-input-file__delete').addEventListener('click', (event) => {
                        event.preventDefault();
                        const permissionForm = event.target.closest(`.${that.permissionFormClass}`);
                        const dataToSend = `action=delPermissionDoc&id=${permissionForm.dataset.stageId}`;

                        Utils.send(dataToSend, '/tests/reports/input-update.json', {
                            // success(response) {
                            // },
                            error(error) {
                                console.error(error);
                            }
                        });
                    });

                    break;
                }
                case 'select-one': {
                    this.initSelect(input);
                    break;
                }
                default: break;
            }
        });
    }

    /**
     * Заполнение инпутов данными с сервера и выставлнеие статуса блоку формы (зеленый фон)
     */
    _getInputsValues() {
        this.inputs.forEach((input) => {
            this.inputsData.fields.forEach((fieldData) => {
                if (input.id === fieldData.id) {
                    switch (input.type) {
                        case 'radio': {
                            input.checked = fieldData.checked;
                            // if (input.checked) {
                            //     mediator.publish('radioChecked', input);
                            // }
                            break;
                        }
                        case 'select-one': {
                            input.value = fieldData.value;
                            break;
                        }
                        case 'file': {
                            const fileInputWrapper = input.closest(`.${this.fileInputClass}`);
                            const pseudoInput = fileInputWrapper.querySelector('.b-input-file__text');

                            pseudoInput.textContent = fieldData.value;
                            if (fieldData.value) {
                                Utils.hide(fileInputWrapper.querySelector('.b-input-file__add'));
                                Utils.show(fileInputWrapper.querySelector('.b-input-file__delete'));
                            }
                            break;
                        }
                        default:
                            input.value = fieldData.value;
                            break;
                    }
                }
            });
            // Выставление статуса инпутам
            // Скипаем поля, находящиеся в дополнительной форме
            if (!input.closest(`.${this.permissionFormClass}`)) {
                this.inputsStatus[input.id] = this.getInputStatus(input);
            }
        });
        this.setBlockStatus();
    }

    getInputStatus(input) {
        switch (input.type) {
            case 'radio': {
                const checkboxGroup = input.closest('.b-radio-row').querySelectorAll('input[type="radio"]');

                for (let i = 0; i < checkboxGroup.length; i++) {
                    if (checkboxGroup[i].checked) {
                        return 'filled';
                    }
                }

                if (input.closest('.b-radio-row').querySelector('input[type="radio"]')) {
                    return 'filled';
                }
                break;
            }
            default: {
                if (input.value) {
                    return 'filled';
                }
                break;
            }
        }

        return 'empty';
    }

    setBlockStatus() {
        for (const key in this.inputsStatus) {
            if (this.inputsStatus[key] !== 'filled') {
                // this.isBlockApproved = false;
                this.target.dataset.approved = 'false';
                this.target.classList.remove(this.approveClass);
                mediator.publish('blockStatusChanged');

                return;
            }
        }

        // this.isBlockApproved = true;
        this.target.dataset.approved = 'true';
        this.target.classList.add(this.approveClass);
        mediator.publish('blockStatusChanged');
    }

    sendNewValue(input) {
        const dataToSend = `action=update&${$(input).serialize()}`;
        const that = this;

        Utils.send(dataToSend, '/tests/reports/input-update.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    // const errorMessage = response.request.errors.join('</br>');

                    // that.showErrorMessage(input, errorMessage);
                } else if (response.request.status === that.SUCCESS_STATUS) {
                    that.inputsStatus[input.id] = that.getInputStatus(input);
                    that.setBlockStatus();
                    // that.removeErrorMessage(input);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    /**
     * Подстановка формы для блока со стадиями строительства
     * @param {String} stageID - ID стадии строительства
     * @param {Boolean} hasExtraForm - флаг необходимости подстановки ф ормы с дополнительными полями
     * @param {Boolean} deletable - флаг ууазвающий необходимомть отображения кнопки удаления стадии
     */
    insertStageForm(stageID, hasExtraForm, deletable = false) {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);

        Utils.insetContent(stageBlock, templateStageForm({
            stageID,
            deletable
        }));
        if (hasExtraForm) {
            const stageSelect = this.target.querySelector(`.${this.stageSelectClass}[data-stage-id="${stageID}"]`);

            stageSelect.insertAdjacentHTML('afterend', templatePermissionForm({id: stageID}));
        }

        // Биндим событя на кнопку удаления
        if (deletable) {
            this.bindRemoveStage(stageID);
        }
    }

    bindRemoveStage(stageID) {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const deleteButtonSelector = `.j-delete-stage[data-stage-id="${stageID}"]`;
        const deleteStageButton = stageBlock.querySelector(deleteButtonSelector);

        deleteStageButton.addEventListener('click', (event) => {
            this.removeStage(event.target);
        });
    }

    removeStage(input) {
        const that = this;
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const dataToSend = `action=delStage&id=${input.dataset.stageId}`;
        const elementsToDelete = Array.from(this.target.querySelectorAll(`[data-stage-id="${input.dataset.stageId}"]`));

        Utils.send(dataToSend, '/tests/reports/input-update.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                elementsToDelete.forEach((element) => {
                    Utils.removeElement(element);
                });

                // Сбрасываем статус блока после удаления стадии
                delete that.inputsStatus[`construction-stage[${input.dataset.stageId}]`];
                that.setBlockStatus();

                // Если осталась только одна стадия - её нельзя удалять
                const onlyOne = 1;

                if (stageBlock.querySelectorAll(`.${that.stageDeleteButtonClass}`).length === onlyOne) {
                    Utils.removeElement(stageBlock.querySelector(`.${that.stageDeleteButtonClass}`));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    stageSelectHandler(event) {
        const selectWrapper = event.target.closest(`.${this.stageSelectClass}`);
        const stageID = selectWrapper.dataset.stageId;
        const needExtraForm = event.target.value === 'stage4' || event.target.value === 'stage6';
        const permissionForm = this.target.querySelector(`.${this.permissionFormClass}[data-stage-id="${stageID}"]`);

        if (needExtraForm && !permissionForm) {
            selectWrapper.insertAdjacentHTML('afterend', templatePermissionForm({id: stageID}));
            const extraFomInputs = Array.from(selectWrapper.nextSibling.querySelectorAll('input, select'));

            this._bindInputsEvents(extraFomInputs);
        } else if (!needExtraForm && permissionForm) {
            Utils.removeElement(permissionForm);
        }

        event.target.closest('.b-input-block').classList.remove(this.untouchedIputClass);
        this.sendNewValue(event.target);
    }

    addStage() {
        const stageBlock = this.target.querySelector(`.${this.stageBlockClass}`);
        const stageAddButton = this.target.querySelector(`.${this.stageAddButtonClass}`);
        const that = this;

        stageAddButton.addEventListener('click', () => {
            Utils.send('action=addConstructionStage', '/tests/reports/add-stage.json', {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const stageID = response.data.ID;

                    that.insertStageForm(stageID, false, true);
                    const newStageSelect = stageBlock.querySelector(`[data-stage-id="${stageID}"] select`);

                    that.initSelect(newStageSelect);

                    // Сбрасываем статус блока после добавления новой стадии
                    that.inputsStatus[newStageSelect.id] = that.getInputStatus(newStageSelect);
                    that.setBlockStatus();
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    /**
     * Подстановка формы для блока со странами экспорта
     * @param {String} groupID - ID группы стран
     * @param {Boolean} deletable - флаг ууазвающий необходимомть отображения кнопки удаления формы
     */
    insertExportGroupForm(groupID, deletable = false) {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);

        Utils.insetContent(groupsBlock, templateExportForm({
            groupID,
            deletable
        }));

        // Биндим событя на кнопку удаления
        if (deletable) {
            this.bindRemoveExportGroup(groupID);
        }
    }

    addExportGroup() {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const groupAddButton = this.target.querySelector(`.j-export-group-add`);
        const that = this;

        groupAddButton.addEventListener('click', () => {
            Utils.send('action=addExportGroup', '/tests/reports/add-stage.json', {
                success(response) {
                    if (response.request.status === that.FAIL_STATUS) {
                        return;
                    }
                    const groupID = response.data.ID;

                    that.insertExportGroupForm(groupID, true);
                    // Сбрасываем статус блока после добавления новой группы
                    const inputs = groupsBlock.querySelectorAll(`[data-group-id="${groupID}"] input`);

                    Array.from(inputs).forEach((input) => {
                        that.inputsStatus[input.id] = that.getInputStatus(input);
                    });
                    that.setBlockStatus();
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    bindRemoveExportGroup(groupID) {
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const deleteButtonSelector = `.j-delete-group[data-group-id="${groupID}"]`;
        const deleteGroupButton = groupsBlock.querySelector(deleteButtonSelector);

        deleteGroupButton.addEventListener('click', (event) => {
            this.removeExportGroup(event.target);
        });
    }

    removeExportGroup(input) {
        const that = this;
        const groupsBlock = this.target.querySelector(`.j-export-groups-block`);
        const dataToSend = `action=delExportGroup&id=${input.dataset.groupId}`;
        const elementsToDelete = Array.from(this.target.querySelectorAll(`[data-group-id="${input.dataset.groupId}"]`));

        Utils.send(dataToSend, '/tests/reports/input-update.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }

                elementsToDelete.forEach((element) => {
                    Utils.removeElement(element);
                });

                // Сбрасываем статус блока после удаления стадии
                delete that.inputsStatus[`export-countries[${input.dataset.groupId}]`];
                delete that.inputsStatus[`export-code[${input.dataset.groupId}]`];
                that.setBlockStatus();

                // Если осталась только одна группа - её нельзя удалять
                const onlyOne = 1;

                if (groupsBlock.querySelectorAll(`.j-delete-group`).length === onlyOne) {
                    Utils.removeElement(groupsBlock.querySelector(`.j-delete-group`));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }
}

export default ReportBlock;
