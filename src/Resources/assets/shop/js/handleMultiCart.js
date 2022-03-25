import handleCartWidget from "./handleCartWidget";
import handleSummary from "./handleSummary"

export class handleMultiCart {
    constructor() {
        this.widget = new handleCartWidget(this.update);
        this.summary = new handleSummary(this.update);
    }

    init = () => {
        if (document.querySelector('#popup-carts')) {
            this.widget.init()
        }
        if (document.querySelector('.multi-cart')) {
            this.summary.init()
        }
    }

    update = () => {
        this.widget.updateCartWidget()
        this.summary.updateSummaryCarts()
    }
}


export default handleMultiCart;