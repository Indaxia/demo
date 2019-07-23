import axios, { AxiosPromise } from 'axios'
import EventManager, { ServiceEvent } from '../services/EventManager' 

class Instance {
    constructor() {
        /** @member {Object} data */
        this.data = {};
    }

    /**
     * Loads initial data
     * @returns {AxiosPromise}
     */
    load() {
        return axios
            .get('/api/initial')
            .then(response => {
                this.data = response.data;
                EventManager.emit('InitialDataStore.loaded', this.data);
            })
    }

    /**
     * @param {function(ServiceEvent)} handler
     */
    subscribe(handler) {
        EventManager.on('InitialDataStore.loaded', handler);
    }
};

export default new Instance;