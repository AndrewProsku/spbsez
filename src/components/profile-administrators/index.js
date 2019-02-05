import $ from 'jquery';
import InputTel from '../../components/forms/telephone/telephone';
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
            this.bindEventsAdmin(adminBlock);
        });
    }

    bindEventsAdmin(adminBlock) {
        const adminInputs = Array.from(adminBlock.querySelectorAll(`.${this.formBlockClass} > input`));

        adminInputs.forEach((input) => {
            input.addEventListener('change', (event) => {
                this.onChange(adminBlock.dataset.id, event.target);
            });
        });
        $(adminBlock.querySelector('.j-select')).change((event) => {
            this.onChange(adminBlock.dataset.id, event.target);
        });

        // Мультиселект
        this.bindAccessSelect(adminBlock);
        this.bindRemoveAdmin(adminBlock);
    }

    bindRemoveAdmin(adminBlock) {
        const that = this;
        const removeButton = adminBlock.querySelector('.j-delete-administrator');

        removeButton.addEventListener('click', () => {
            that.removeAdmin(adminBlock);
        });
    }

    bindAccessSelect(adminBlock) {
        const selectAccordion = adminBlock.querySelector('.b-select-accordion');
        const accordionSubmit = selectAccordion.querySelector('.b-select-accordion__button');

        this.setAccessesInputValue(selectAccordion);

        adminBlock.querySelector('.j-select-accordion').addEventListener('click', (event) => {
            if (!event.target.closest('.b-select-accordion__list')) {
                selectAccordion.classList.toggle('is-open');
            }

            document.addEventListener('click', (clickEvent) => {
                if (!clickEvent.target.closest('.j-select-accordion')) {
                    selectAccordion.classList.remove('is-open');
                }
            }, false);
        });

        // Клик по сабмиту выпадайки
        accordionSubmit.addEventListener('click', () => {
            const that = this;
            const selectAccordionClosure = selectAccordion;
            const accessesClosure = this.setAccessesInputValue(selectAccordion);
            let dataToSend = `action=updateAdmin&id=${adminBlock.dataset.id}`;

            for (const access in accessesClosure) {
                if (Object.prototype.hasOwnProperty.call(accessesClosure, access)) {
                    dataToSend += `&${access}=${accessesClosure[access]}`;
                }
            }

            Utils.send(dataToSend, '/api/profile/', {
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
    }

    setAccessesInputValue(selectAccordion) {
        const accordionInput = selectAccordion.querySelector('.b-input-text');
        const accesses = {};
        const accessFields = {};
        const textValue = [];

        selectAccordion.querySelectorAll('.b-checkbox-input').forEach((checkbox) => {
            if (checkbox.checked) {
                let label = Array.from(checkbox.nextElementSibling.getElementsByClassName('b-checkbox-text'));

                accessFields[checkbox.name] = checkbox.value;

                if (label) {
                    label = label.shift();
                    accesses[checkbox.id] = label.innerText;
                }
            }
        });

        for (const access in accesses) {
            if (accesses[access]) {
                textValue.push(accesses[access]);
            }
        }
        accordionInput.value = textValue.join(', ');

        return accessFields;
    }

    addAdmin() {
        const that = this;

        Utils.send('action=addAdmin', '/api/profile/', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }
                const admin = response.data;

                if (!that.adminCount) {
                    Utils.removeElement(document.querySelector('.j-empty-page'));
                }
                that.$administrators.appendChild(that.prepareTemplate(admin));
                that.bindEventsAdmin(that.$administrators.lastElementChild);
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

        Utils.send(dataToSend, '/api/profile/', {
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

    onChange(id, input) {
        const that = this;
        const dataToSend = `action=updateAdmin&id=${id}&${$(input).serialize()}`;

        Utils.send(dataToSend, '/api/profile/', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    const errorMessage = response.request.errors.join('</br>');

                    that.showErrorMessage(input, errorMessage);
                } else if (response.request.status === that.successStatus) {
                    that.removeErrorMessage(input);

                    if (input.name === 'FULL_NAME') {
                        input.closest('.j-profile-block')
                            .querySelector('.b-profile-admin-title').firstElementChild.textContent = input.value;
                    }

                    if (response.data === null) {
                        return;
                    }

                    const originAdminBlock = input.closest('.j-profile-block');

                    const newBlock = originAdminBlock.parentNode
                        .insertBefore(that.prepareTemplate(response.data), originAdminBlock);

                    originAdminBlock.remove();
                    that.bindEventsAdmin(newBlock);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    prepareTemplate(admin) {
        admin.id = admin.ID;

        const newAdminBlock = new DOMParser().parseFromString(templateAdmin(admin), 'text/html').body.firstChild;

        // Инициализируем инпут телефонного номера
        const inputTel = new InputTel();
        const newTelInputs = Array.from(newAdminBlock.querySelectorAll('input[type="tel"]'));

        inputTel.init({input: newTelInputs});

        return newAdminBlock;
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
