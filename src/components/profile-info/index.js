import $ from 'jquery';
import InputTel from '../../components/forms/telephone/telephone';
import templateContact from './templates/contact.twig';
import Utils from '../../common/scripts/utils';

class ProfileInfo {
    constructor() {
        this.$profileInfo = document.querySelector('.j-profile-info');
        this.$adminInfo = this.$profileInfo.querySelector('.j-profile-info-administrator');
        this.$contactsInfo = this.$profileInfo.querySelector('.j-profile-info-contacts');
        this.$companyInfo = this.$profileInfo.querySelector('.j-profile-info-company');
        this.$addContactButton = this.$profileInfo.querySelector('.j-add-contact');

        this.adminInputs = Array.from(this.$adminInfo.querySelectorAll('input'));
        this.companyInputs = Array.from(this.$companyInfo.querySelectorAll('input'));

        this.messageInputClass = 'b-form-block__error-text';
        this.errorInputClass = 'b-form-block-error';

        this.successStatus = 1;
        this.failStatus = 0;

        this.isContactsDeletable = false;
    }

    init() {
        const that = this;

        Utils.send('', '/api/profile/', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }

                const data = response.data;
                const inputTel = new InputTel();

                // Заполняем форму данными
                if (data.profile) {
                    that.initAdministratorInfo(data.profile);
                    that.initCompanyInfo(data.profile);
                }

                if (data.contacts) {
                    // Hi, ESLint
                    const onlyOne = 1;

                    that.isContactsDeletable = data.contacts.length > onlyOne;
                    data.contacts.forEach((contact) => {
                        contact.deletable = that.isContactsDeletable;
                        that.$contactsInfo.insertAdjacentHTML('beforeend', templateContact(contact));
                    });
                }

                const telInputs = Array.from(that.$profileInfo.querySelectorAll('input[type="tel"]'));

                inputTel.init({input: telInputs});
                that.bindEvents();
            },
            error(error) {
                console.error(error);
            }
        });
    }

    bindEvents() {
        const that = this;

        // Отсылаем введенные пользователем данные при изменении значения тестовых полей
        this.adminInputs.forEach((input) => {
            input.addEventListener('change', (event) => {
                this.onChange(event.target);
            });
        });
        this.companyInputs.forEach((input) => {
            input.addEventListener('change', (event) => {
                this.onChange(event.target);
            });
        });

        this.bindEventsContacts();

        // Добавление контактного лица
        this.$addContactButton.addEventListener('click', () => {
            Utils.send('action=addContact', '/api/profile/', {
                success(response) {
                    if (response.request.status === that.failStatus) {
                        return;
                    }
                    const contact = {};

                    // TODO Создавать новый элемент через createElement чтобы заранее вешать на него обработчики
                    contact.deletable = that.isContactsDeletable;
                    contact.id = response.data.id;
                    that.$contactsInfo.insertAdjacentHTML('beforeend', templateContact(contact));
                    that.bindEventsContacts();

                    const inputTel = new InputTel();
                    const telInputs = Array.from(that.$contactsInfo.querySelectorAll('input[type="tel"]'));

                    inputTel.init({input: telInputs});
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }

    bindEventsContacts() {
        const contactBlocks = Array.from(this.$contactsInfo.querySelectorAll('.b-profile-block'));

        contactBlocks.forEach((contact) => {
            const contactInputs = Array.from(contact.querySelectorAll('input'));

            contactInputs.forEach((input) => {
                $(input).unbind('change');
                input.addEventListener('change', (event) => {
                    this.onChange(event.target);
                });
            });
        });

        this.bindRemoveContact();
    }

    initAdministratorInfo(data) {
        const that = this;

        if (data.FULL_NAME) {
            that.$adminInfo.querySelector('#profile-name').value = data.FULL_NAME;
        }
        if (data.EMAIL) {
            that.$adminInfo.querySelector('#profile-email').value = data.EMAIL;
        }
        if (data.STATUS) {
            that.$adminInfo.querySelector('#profile-status').value = data.STATUS;
        }
        if (data.WORK_PHONE) {
            that.$adminInfo.querySelector('#profile-phone').value = data.WORK_PHONE;
        }
    }

    initCompanyInfo(data) {
        const that = this;

        if (data.WORK_COMPANY) {
            that.$companyInfo.querySelector('#company-resident').value = data.WORK_COMPANY;
        }
        if (data.UF_INN) {
            that.$companyInfo.querySelector('#company-inn').value = data.UF_INN;
        }
        if (data.UF_ADDR_LEGAL) {
            that.$companyInfo.querySelector('#company-legal-address').value = data.UF_ADDR_LEGAL;
        }
        if (data.UF_ADDR_POST) {
            that.$companyInfo.querySelector('#company-postal-address').value = data.UF_ADDR_POST;
        }
        if (data.WORK_PHONE) {
            that.$companyInfo.querySelector('#company-phone').value = data.WORK_PHONE;
        }
        if (data.WORK_FAX) {
            that.$companyInfo.querySelector('#company-fax').value = data.WORK_FAX;
        }
        if (data.UF_EMAIL) {
            that.$companyInfo.querySelector('#company-email').value = data.UF_EMAIL;
        }
        if (data.UF_OWNER_FIO) {
            that.$companyInfo.querySelector('#company-ceo').value = data.UF_OWNER_FIO;
        }
    }

    bindRemoveContact() {
        const that = this;

        that.contactRemoveButtons = Array.from(that.$contactsInfo.querySelectorAll('.j-delete-contact'));
        that.contactRemoveButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                that.removeContact(event.target);
            });
        });
    }

    removeContact(input) {
        const that = this;
        const dataToSend = `action=delContact&id=${input.dataset.id}`;
        const contactClass = `.b-profile-block[data-id="${input.dataset.id}"]`;
        const contactToDelete = that.$contactsInfo.querySelector(contactClass);

        Utils.send(dataToSend, '/api/profile/', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }

                Utils.removeElement(contactToDelete);

                // Если остался один контакт - его нельзя удалять
                const onlyOne = 1;

                if (that.$contactsInfo.querySelectorAll(`.b-profile-block`).length === onlyOne) {
                    that.isContactsDeletable = false;
                    Utils.removeElement(that.$contactsInfo.querySelector('.j-delete-contact'));
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    onChange(input) {
        const dataToSend = `action=update&${$(input).serialize()}`;
        const that = this;

        Utils.send(dataToSend, '/api/profile/', {
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
        const messageEl = element.parentNode.querySelector(`.${this.messageInputClass}`);

        Utils.clearHtml(messageEl);
        Utils.insetContent(messageEl, message);
        element.parentNode.classList.add(this.errorInputClass);
    }

    removeErrorMessage(element) {
        element.parentNode.classList.remove(this.errorInputClass);
    }
}

export default ProfileInfo;
