import handleCartWidget from './handleCartWidget';
import handleSummary from './handleSummary';

export class handleMultiCart {
    constructor() {
        this.widget;
        this.summary;
    }

    init = () => {
        if (document.querySelector('[data-bb-mc-widget-carts]')) {
            this.widget = new handleCartWidget({
                update: this.update,
            });
            this.widget.init();
        }

        if (document.querySelector('[data-bb-mc]')) {
            this.summary = new handleSummary({
                update: this.update,
            });
            this.summary.init();
        }
    };

    update = () => {
        this.widget?.updateCartWidget();
        this.summary?.updateSummaryCarts();
    };
}

export default handleMultiCart;
