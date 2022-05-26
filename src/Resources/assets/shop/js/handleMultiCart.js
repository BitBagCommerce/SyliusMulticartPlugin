import handleCartWidget from './handleCartWidget';
import handleSummary from './handleSummary';

export class handleMultiCart {
    constructor(config) {
        const defaults = {
            update: this.update,
            widgetCarts: '[data-bb-mc-widget-carts]',
            multiCart: '[data-bb-mc]'
        };
        this.config = { ...defaults, ...config };
        this.widget;
        this.summary;
    }

    init = () => {
        if (document.querySelector(this.config.widgetCarts)) {
            this.widget = new handleCartWidget(this.config);
            this.widget.init();
        }

        if (document.querySelector(this.config.multiCart)) {
            this.summary = new handleSummary(this.config);
            this.summary.init();
        }
    };

    update = () => {
        this.widget?.updateCartWidget();
        this.summary?.updateSummaryCarts();
    };
}

export default handleMultiCart;
