import axios from 'axios'
import InitialDataStore from '../../common/store/InitialDataStore'

/**
 * @typedef {Object} AccessInitialData
 * @property {string} sessionId
 * @property {{ protection: Boolean, token: string, headerName: string }} csrf
 * @see InitialDataProvider
 */

class Instance {
    constructor() {
        this.sessionId = '';

        InitialDataStore.subscribe((event) => {
            /** @type {AccessInitialData} */
            let accessData = event.data.Access;

            this.sessionId = accessData.sessionId;

            if(accessData.csrf.protection) {
                axios.defaults.headers.common[accessData.csrf.headerName] = accessData.csrf.token;
            }
        });
    }
}

export default new Instance;