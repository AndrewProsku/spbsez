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
        this.deleteDocumentWrapClass = '.j-profile-document__item-delete';
        this.documentsPage = document.querySelector(this.selector);
        this.documentsList = this.documentsPage.querySelector('.b-profile-document__list');
        this.fileButton = this.documentsPage.querySelector('.b-profile-add');
        this.fileInput = this.documentsPage.querySelector('.j-profile__file-picker');
        this.docItems = Array.from(this.documentsList.querySelectorAll(`.${this.rowClass}`));
        this.successStatus = 1;
        this.failStatus = 0;
    }

    init() {
        this.bindEvents();

        this.acceptedTypes = this.fileInput.getAttribute('accept').split(',');
        this.setZIndexes();
    }

    bindEvents() {
        this.bindDeleteTooltip();

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

    bindDeleteTooltip() {
        if (this.docItems.length) {
            this.docItems.forEach((element) => {
                const isDeletable = element.dataset.canDelete === 'true';

                if (isDeletable) {
                    const delDocWrap = element.querySelector('.j-profile-document__item-delete');
                    const delDoc = element.querySelector('.j-delete-doc-button');

                    delDoc.addEventListener('click', (event) => {
                        event.preventDefault();

                        element.classList.toggle('is-active-item');
                        delDocWrap.classList.toggle('is-active-delete');

                        delDoc.classList.toggle('is-delete');
                    });
                } else if (element.querySelector(`.${this.deleteDocumentWrapClass}`)) {
                    Utils.removeElement(element.querySelector(`.${this.deleteDocumentWrapClass}`));
                }
            });
        }
    }

    setZIndexes() {
        const documentRows = Array.from(document.querySelectorAll(`.${this.rowClass}`));

        this.documentsAmount = documentRows.length;
        for (let i = this.documentsAmount - 1, zIndex = 0; i >= 0; i--) {
            documentRows[i].style.zIndex = zIndex;
            zIndex++;
        }
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
        const that = this;
        const templateData = documentInfo;

        templateData.canDelete = String(documentInfo.canDelete);
        const template = documentTemplate(templateData);
        const documentItem = new DOMParser().parseFromString(template, 'text/html').body.firstChild;

        documentItem.style.zIndex = ++this.documentsAmount;

        if (documentInfo.canDelete) {
            const delDocWrap = documentItem.querySelector('.j-profile-document__item-delete');
            const delDoc = documentItem.querySelector('.j-delete-doc-button');
            const delDocButton = documentItem.querySelector('.j-delete-doc-row');

            delDoc.addEventListener('click', (event) => {
                event.preventDefault();

                documentItem.classList.toggle('is-active-item');
                delDocWrap.classList.toggle('is-active-delete');

                delDoc.classList.toggle('is-delete');
            });

            delDocButton.addEventListener('click', (event) => {
                event.preventDefault();
                that.removeDoc(event.target.closest(`.${that.rowClass}`));
            });
        }

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

        this.delButtons = Array.from(this.documentsPage.querySelectorAll('.j-delete-doc-row'));
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
