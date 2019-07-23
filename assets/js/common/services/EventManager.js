export class ServiceEvent {
    /**
     * @param {string} name 
     * @param {*} data 
     * @param {Boolean} once 
     */
    constructor(name, data = null, once = false) {
        this.name = name;
        this.data = data;
        this.once = once;
        this.propagate = true;
    }
}

export class ServiceEventSubscriber {
    /**
     * @param {function(ServiceEvent)} handler 
     * @param {*} once Set to true to execute it once and then remove the subscriber
     */
    constructor(handler, once = false) {
        this.handler = handler;
        this.once = once;
    }
}

class EventManager {
    constructor() {
        this.subscribers = {};
        this.debug = false;
    }

    /**
     * Subscribes a handler function on event
     * @param {string|string[]} eventNameOrNames
     * @param {function(ServiceEvent)} handler
     */
    on(eventNameOrNames, handler) {
        this.debug && console.debug('EventManager', 'on', eventNameOrNames);
        this.__on(eventNameOrNames, handler, false);
    }

      
    /**
     * Subscribes a handler function on event once, un-subscribes after first emit
     * @param {string|string[]} eventNameOrNames
     * @param {function(ServiceEvent)} handler
     */
    once(eventNameOrNames, handler) {
      this.debug && console.debug('EventManager', 'once', eventNameOrNames);
      this.__on(eventNameOrNames, handler, true);
    }

    /**
     * @param {string} eventName
     * @param {*} [data]
     */
    emit(eventName, data) {
      let event = new ServiceEvent(eventName, data, true, false);

      this.debug && console.debug('EventManager', 'emit', event);

      if (this.subscribers.hasOwnProperty(eventName)) {
        for (let i = this.subscribers[eventName].length - 1; i > -1; --i) {
          let subscriber = this.subscribers[eventName][i];
          if (subscriber.once) {
            this.subscribers[eventName].splice(i, 1);
            event.once = true;
          }
          subscriber.handler(event); // call handler
          if (!event.propagate) {
            break;
          }
        }
      }
    }

    /**
     * Removes event subscription
     * @param {string|string[]} eventNameOrNames
     * @param {function(ServiceEvent)} handler
     */
    unsubscribe(eventNameOrNames, handler) {
      this.debug && console.debug('EventManager', 'unsubscribe', eventNameOrNames);

      let eventNames = (typeof eventNameOrNames === 'string') ? [eventNameOrNames] : eventNameOrNames;

      eventNames.forEach((eventName) => {
        if (typeof eventName === 'string') {
          if (this.subscribers.hasOwnProperty(eventName)) {
            for(let i = this.subscribers[eventName].length - 1; i > -1; --i) {
              if(this.subscribers[eventName][i].handler === handler) {
                  this.subscribers[eventName].splice(i, 1);
              }
            }

            if(! this.subscribers[eventName].length) {
              delete this.subscribers[eventName];
            }
          }
        } else {
          throw new Error('EventManager: eventName is not a string, got ' + (typeof eventName));
        }
      });
    }


    /**
     * @param {string|string[]} eventNameOrNames
     * @param {function(ServiceEvent)} handler
     * @param {boolean} once if true, un-subscribes after first emit
     */
    __on(eventNameOrNames, handler, once) {
      let eventNames = (typeof eventNameOrNames === 'string') ? [eventNameOrNames] : eventNameOrNames;

      eventNames.forEach((eventName) => {
        if (typeof eventName === 'string') {
          if (!this.subscribers.hasOwnProperty(eventName)) {
              this.subscribers[eventName] = [];
          }

          this.subscribers[eventName].push(new ServiceEventSubscriber(handler, once));
        } else {
          throw new Error('EventManager: eventName is not a string, got ' + (typeof eventName));
        }
      });
    }
}

export default new EventManager;