import $ from 'jquery';
import Utils from '../../common/scripts/utils';

class ProfileDocs {
    static get selector() {
        return '.j-profile-documents';
    }

    constructor() {
        this.selector = ProfileDocs.selector;
        this.rowClass = 'j-profile-document__item';
        this.fileButton = document.querySelector('.j-profile__file-picker');
        this.delButtons = document.querySelectorAll('.j-delete-doc-row');
        this.successStatus = 1;
        this.failStatus = 0;
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        this.fileButton.addEventListener('change', (event) => {
            $(event.target).closest('form')
                .submit();
        });

        this.bindRemoveDocs();
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
                if (response.request.status === that.failStatus) {
                    return;
                }

                Utils.removeElement(element);
            },
            error(error) {
                console.error(error);
            }
        });
    }
}

export default ProfileDocs;
