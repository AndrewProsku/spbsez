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

        Utils.send('', '/tests/personal-info.json', {
            success(response) {
                if (response.request.status === that.failStatus) {
                    return;
                }

                const data = response.data;
                const inputTel = new InputTel();

                // Заполняем форму данными
                if (data.administrator) {
                    that.initAdministratorInfo(data.administrator);
                }

                if (data.company) {
                    that.initCompanyInfo(data.company);
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

        const contactBlocks = Array.from(this.$contactsInfo.querySelectorAll('.b-profile-block'));

        contactBlocks.forEach((contact) => {
            const contactInputs = Array.from(contact.querySelectorAll('input'));

            contactInputs.forEach((input) => {
                input.addEventListener('change', (event) => {
                    this.onChange(event.target);
                });
            });
        });


        // Добавление контактного лица
        this.$addContactButton.addEventListener('click', () => {
            Utils.send('addContact', '/tests/personal-info.json', {
                success(response) {
                    if (response.request.status === that.failStatus) {
                        return;
                    }
                    const contact = {};

                    // TODO Создавать новый элемент через createElement чтобы заранее вешать на него обработчики
                    contact.deletable = that.isContactsDeletable;
                    contact.id = response.data.id;
                    that.$contactsInfo.insertAdjacentHTML('beforeend', templateContact(contact));

                    that.bindRemoveContact();

                    const inputTel = new InputTel();
                    const telInputs = Array.from(that.$contactsInfo.querySelectorAll('input[type="tel"]'));

                    inputTel.init({input: telInputs});
                },
                error(error) {
                    console.error(error);
                }
            });
        });

        // Удаление контактоного лица
        this.bindRemoveContact();
    }

    initAdministratorInfo(data) {
        const that = this;

        if (data.name) {
            that.$adminInfo.querySelector('#profile-name').value = data.name;
        }
        if (data.email) {
            that.$adminInfo.querySelector('#profile-email').value = data.email;
        }
        if (data.status) {
            that.$adminInfo.querySelector('#profile-status').value = data.status;
        }
        if (data.phone) {
            that.$adminInfo.querySelector('#profile-phone').value = data.phone;
        }
    }

    initCompanyInfo(data) {
        const that = this;

        if (data.resident) {
            that.$companyInfo.querySelector('#company-resident').value = data.resident;
        }
        if (data.inn) {
            that.$companyInfo.querySelector('#company-inn').value = data.inn;
        }
        if (data.legalAddress) {
            that.$companyInfo.querySelector('#company-legal-address').value = data.legalAddress;
        }
        if (data.postalAddress) {
            that.$companyInfo.querySelector('#company-postal-address').value = data.postalAddress;
        }
        if (data.phone) {
            that.$companyInfo.querySelector('#company-phone').value = data.phone;
        }
        if (data.fax) {
            that.$companyInfo.querySelector('#company-fax').value = data.fax;
        }
        if (data.email) {
            that.$companyInfo.querySelector('#company-email').value = data.email;
        }
        if (data.ceo) {
            that.$companyInfo.querySelector('#company-ceo').value = data.ceo;
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
        const dataToSend = `removeContact=${input.dataset.id}`;
        const contactClass = `.b-profile-block[data-id="${input.dataset.id}"]`;
        const contactToDelete = that.$contactsInfo.querySelector(contactClass);


        Utils.send(dataToSend, '/tests/personal-info-add-contact.json', {
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
        const dataToSend = $(input).serialize();
        const that = this;

        Utils.send(dataToSend, '/tests/personal-info.json', {
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
