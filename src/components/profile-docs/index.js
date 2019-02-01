import documentTemplate from './document.twig';
import errorMessageTemplate from './errorMessage.twig';
import Utils from '../../common/scripts/utils';

class ProfileDocs {
    static get selector() {
        return '.j-profile-documents';
    }

    constructor() {
        this.selector = ProfileDocs.selector;
        this.rowClass = 'j-profile-document__item';
        this.errorMessageClass = 'b-profile-document-error';
        this.documentsPage = document.querySelector(this.selector);
        this.documentsList = this.documentsPage.querySelector('.b-profile-document__list');
        this.fileButton = this.documentsPage.querySelector('.b-profile-add');
        this.fileInput = this.documentsPage.querySelector('.j-profile__file-picker');
        this.delButtons = this.documentsPage.querySelectorAll('.j-delete-doc-row');
        this.successStatus = 1;
        this.failStatus = 0;
    }

    init() {
        this.bindEvents();

        this.acceptedTypes = this.fileInput.getAttribute('accept').split(',');
    }

    bindEvents() {
        this.fileButton.querySelector('form').addEventListener('submit', (event) => {
            event.preventDefault();
        });

        const that = this;

        this.fileInput.addEventListener('change', (event) => {
            const FIRST_ELEMENT = 0;
            const file = event.target.files[FIRST_ELEMENT];

            if (!this.validFileType(file.type)) {
                const wrongTypeMessage = this.createMessageBlock();

                this.documentsPage.insertBefore(wrongTypeMessage, this.fileButton);

                return;
            }

            const formData = new FormData(event.target.closest('form'));

            Utils.send(formData, '/api/profile/', {
                success(response) {
                    if (!response.request.status === that.successStatus) {
                        return;
                    }

                    response.data.docs.forEach((row) => {
                        that.documentsList.insertBefore(that.createDocumentItem(row), that.documentsList.firstChild);
                    });
                    that.removeErrorMessages();
                },
                error(error) {
                    console.error(error);
                }
            });
        });

        this.bindRemoveDocs();
    }

    createMessageBlock() {
        const template = errorMessageTemplate();
        const errorMessageBlock = new DOMParser().parseFromString(template, 'text/html').body.firstChild;

        errorMessageBlock.querySelector('.b-profile-document-error__close').addEventListener('click', (event) => {
            Utils.removeElement(event.target.closest('.b-profile-document-error'));
        });

        return errorMessageBlock;
    }

    createDocumentItem(documentInfo) {
        const template = documentTemplate(documentInfo);
        const documentItem = new DOMParser().parseFromString(template, 'text/html').body.firstChild;
        const delDocWrap = documentItem.querySelector('.j-profile-document__item-delete');
        const delDoc = documentItem.querySelector('.j-delete-doc-button');

        delDoc.addEventListener('click', (event) => {
            event.preventDefault();

            documentItem.classList.toggle('is-active-item');
            delDocWrap.classList.toggle('is-active-delete');

            delDoc.classList.toggle('is-delete');
        });

        return documentItem;
    }

    validFileType(fileType) {
        let isValid = false;

        for (const type of this.acceptedTypes) {
            if (type === fileType) {
                isValid = true;
                break;
            }
        }

        return isValid;
    }

    bindRemoveDocs() {
        const that = this;

        this.delButtons = Array.from(this.delButtons);
        this.delButtons.forEach((el) => {
            el.addEventListener('click', (event) => {
                event.preventDefault();
                that.removeDoc(event.target.closest(`.${that.rowClass}`));
            });
        });
    }

    removeDoc(element) {
        const that = this;
        const dataToSend = `action=delDoc&id=${element.dataset.id}`;

        Utils.send(dataToSend, '/api/profile/', {
            success(response) {
                if (response.request.status === that.successStatus) {
                    Utils.removeElement(element);
                }
            },
            error(error) {
                console.error(error);
            }
        });
    }

    removeErrorMessages() {
        const errorMessages = Array.from(this.documentsPage.querySelectorAll(`.${this.errorMessageClass}`));

        if (errorMessages.length) {
            errorMessages.forEach((message) => {
                Utils.removeElement(message);
            });
        }
    }
}

export default ProfileDocs;
