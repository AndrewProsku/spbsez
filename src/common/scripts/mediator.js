/* eslint-disable consistent-this */
let instance = null;

class Mediator {
    constructor() {
        if (!instance) {
            instance = this;
        }

        this.channels = {};

        return instance;
    }

    subscribe(channel, fn) {
        if (!this.channels[channel]) {
            this.channels[channel] = [];
        }

        this.channels[channel].push({
            context : this,
            callback: fn
        });
    }

    publish(channel) {
        if (!this.channels[channel]) {
            return false;
        }

        const firstArgument = 1;
        const args = Array.prototype.slice.call(arguments, firstArgument);

        this.channels[channel].forEach((subscription) => {
            subscription.callback.apply(subscription.context, args);
        });

        return this;
    }

    installTo(obj) {
        obj.channels = {};

        obj.publish = this.publish;
        obj.subscribe = this.subscribe;
    }
}

export default Mediator;
/* eslint-enable consistent-this */
