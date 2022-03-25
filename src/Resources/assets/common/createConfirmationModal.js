export class CreateConfirmationModal {
    constructor(
        config = {},
        actions = {
            performAction: () => { },
            cancelAction: () => { },
        }
    ) {
        this.config = config;
        this.defaultConfig = {
            headerTitle: 'Confirmation modal',
            bodyText: 'Are you sure, you want to perform this action?',
            cancelText: 'cancel',
            performText: 'perform',
        };
        this.finalConfig = { ...this.defaultConfig, ...config };
        this.actions = actions;
        this.modal;
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - CreateConfirmationModal - given config is not valid - expected object');
        }
        this._renderModal();
    }

    _renderModal() {
        this.modal = this._modalTemplate();
        this.modal.classList.add('bitbag', 'modal-initialization');
        this._modalActions(this.modal);
        document.querySelector('body').appendChild(this.modal);
        this.modal.classList.remove('modal-initialization');
        this.modal.classList.add('modal-initialized');
    }

    _modalTemplate() {
        const modal = document.createElement('div');
        modal.innerHTML = `    
        <article class="confirmation-modal">
            <header class="confirmation-modal__header">
                <h2 class="confirmation-modal__header--title">${this.finalConfig.headerTitle}</h2>
            </header>
            <section class="confirmation-modal__body">
                <p class="confirmation-modal__body--text">${this.finalConfig.bodyText}</p>
            </section>
            <section class="confirmation-modal__confirm">
                <button type="button" data-bb-action="cancel" class="confirmation-modal__confirm--cancel">${this.finalConfig.cancelText}</button>
                <button type="button" data-bb-action="perform" class="confirmation-modal__confirm--perform">${this.finalConfig.performText}</button>
            </section>
        </article>
      `;

        return modal;
    }

    _modalActions(template) {
        const cancelBtn = template.querySelector('[data-bb-action="cancel"]');
        const confirmBtn = template.querySelector('[data-bb-action="perform"]');

        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.actions.cancelAction();
            this._closeModal();
        });

        confirmBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.actions.performAction();
            this._closeModal();
        });
    }

    _closeModal() {
        this.modal.remove();
    }
}

export default CreateConfirmationModal;