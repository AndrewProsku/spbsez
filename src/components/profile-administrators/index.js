import $ from 'jquery';
import InputTel from '../../components/forms/telephone/telephone';
import Select from '../../components/forms/select/';
import templateAdmin from './administrator.twig';
import Utils from '../../common/scripts/utils';

class ProfileAdministrators {
    constructor() {
        this.$administrators = document.querySelector('.j-profile-administrators');
        this.$addAdminButton = document.querySelector('.j-add-administrator');

        this.formBlockClass = 'b-form-block';
        this.messageInputClass = 'b-form-block__error-text';
        this.errorInputClass = 'b-form-block-error';
        this.successStatus = 1;
        this.failStatus = 0;
        this.adminCount = 0;
        this.accessText = {
            access1: 'Подача отчета',
            access2: 'Сообщения от ОЭЗ',
            access3: 'Подача заявки'
        };
    }

    init() {
        this.bindEvents();

        const inputTel = new InputTel();
        const telInputs = Array.from(this.$administrators.querySelectorAll('input[type="tel"]'));

        inputTel.init({input: telInputs});
    }

    bindEvents() {
        // Добавление контактного лица
        this.$addAdminButton.addEventListener('click', () => {
            this.addAdmin();
        });


        const adminBlocks = Array.from(this.$administrators.querySelectorAll('.j-profile-block'));

        adminBlocks.forEach((adminBlock) => {
            this.adminCount += 1;

            // Мультиселект
            const selectAccordion = adminBlock.querySelector('.b-select-accordion');
            const accordionSubmit = selectAccordion.querySelector('.b-select-accordion__button');
            const accesses = this.setAccessesInputValue(selectAccordion);

            adminBlock.querySelector('.j-select-accordion').addEventListener('click', (event) => {
                if (!event.target.closest('.b-select-accordion__list')) {
                    selectAccordion.classList.toggle('is-open');
                }
            });

            // Клик по сабмиту выпадайки
            accordionSubmit.addEventListener('click', () => {
                const that = this;
                const selectAccordionClosure = selectAccordion;
                const accessesClosure = accesses;
                let dataToSend = `action=changeAccess&id=${adminBlock.dataset.id}`;

                for (const access in accesses) {
                    if ({}.hasOwnProperty.call(accesses, access)) {
                        dataToSend += `&${access}=${accesses[access]}`;
                    }
                }

                Utils.send(dataToSend, '/tests/administrators.json', {
                    success(response) {
                        if (response.request.status === that.successStatus) {
                            that.setAccessesInputValue(selectAccordionClosure);

                            that.removeErrorMessage(selectAccordionClosure);
                        } else if (response.request.status === that.failStatus) {
                            const errorMessage = response.request.errors.join('</br>');

                            that.showErrorMessage(selectAccordionClosure, errorMessage);

                            // Вернем значения чекбоксов к прежнему состоянию
                            const checkboxes = Array.from(selectAccordionClosure.querySelectorAll('.b-checkbox-input'));

                            checkboxes.forEach((checkbox) => {
                                checkbox.checked = accessesClosure[checkbox.name];
                            });
                        }
                    },
                    error(error) {
                        console.error(error);
                    }
                });

                selectAccordion.classList.remove('is-open');
            });

            this.bindEventsAdmin(adminBlock);
        });
    }

    setAccessesInputValue(selectAccordion) {
        const accordionInput = selectAccordion.querySelector('.b-input-text');
        const accordionCheckboxes = Array.from(selectAccordion.querySelectorAll('.b-checkbox-input'));
        const accesses = {};
        const textValue = [];

        accordionCheckboxes.forEach((checkbox) => {
            accesses[checkbox.name] = checkbox.checked;
        });

        for (const access in accesses) {
            if (accesses[access]) {
                textValue.push(this.accessText[access]);
            }
        }
        accordionInput.value = textValue.join(', ');

        return accesses;
    }

    bindEventsAdmin(adminBlock) {
        const adminInputs = Array.from(adminBlock.querySelectorAll(`.${this.formBlockClass} > input`));

        adminInputs.forEach((input) => {
            input.addEventListener('change', (event) => {
                this.onChange(event.target);
            });
        });
        $(adminBlock.querySelector('.j-select')).change((event) => {
            this.onChange(event.target);
        });

        this.bindRemoveAdmin(adminBlock);
    }

    bindRemoveAdmin(adminBlock) {
        const that = this;
        const removeButton = adminBlock.querySelector('.j-delete-administrator');

        removeButton.addEventListener('click', () => {
            that.removeAdmin(adminBlock);
        });
    }

    addAdmin() {
        const that = this;

        Utils.send('action=addAdmin', '/tests/personal-info-add-contact.json', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }
                const admin = {
                    id: response.data.id
                };
                const template = templateAdmin(admin);
                const newAdminBlock = new DOMParser().parseFromString(template, 'text/html').body.firstChild;

                // Инициализируем инпут телефонного номера
                const inputTel = new InputTel();
                const newTelInputs = Array.from(newAdminBlock.querySelectorAll('input[type="tel"]'));

                inputTel.init({input: newTelInputs});

                // Инициализируем кастомный select
                const selectInput = newAdminBlock.querySelector('.j-select');
                const select = new Select({
                    element      : selectInput,
                    disableSearch: true
                });

                select.init();


                that.bindEventsAdmin(newAdminBlock);

                if (!that.adminCount) {
                    Utils.removeElement(document.querySelector('.j-empty-page'));
                }
                that.$administrators.appendChild(newAdminBlock);
                that.adminCount += 1;
            },
            error(error) {
                console.error(error);
            }
        });
    }

    removeAdmin(element) {
        const that = this;
        const dataToSend = `action=delAdmin&id=${element.dataset.id}`;

        Utils.send(dataToSend, '/tests/administrators.json', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }

                Utils.removeElement(element);
                that.adminCount -= 1;

                if (!that.adminCount) {
                    that.showEmptyPage();
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    onChange(input) {
        const that = this;
        const dataToSend = `action=update&${$(input).serialize()}`;

        Utils.send(dataToSend, '/tests/administrators.json', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    const errorMessage = response.request.errors.join('</br>');

                    that.showErrorMessage(input, errorMessage);
                } else if (response.request.status === that.successStatus) {
                    that.removeErrorMessage(input);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    showErrorMessage(element, message) {
        const parentFormBlock = element.closest(`.${this.formBlockClass}`);
        const messageEl = parentFormBlock.querySelector(`.${this.messageInputClass}`);

        Utils.clearHtml(messageEl);
        Utils.insetContent(messageEl, message);
        parentFormBlock.classList.add(this.errorInputClass);
    }

    removeErrorMessage(element) {
        element.closest(`.${this.formBlockClass}`).classList.remove(this.errorInputClass);
    }

    showEmptyPage() {
        const template = `<div class="b-empty-page j-empty-page is-active"><p>Администраторов пока нет</p></div>`;
        const emptyPageBlock = new DOMParser().parseFromString(template, 'text/html').body.firstChild;

        this.$administrators.parentNode.insertBefore(emptyPageBlock, this.$administrators);
    }
}

export default ProfileAdministrators;
