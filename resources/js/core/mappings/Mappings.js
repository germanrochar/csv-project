import Mapping from './Mapping'
export default class Mappings {

    /**
     * Initialize the class using a list of keys for mappings
     * @param keys
     */
    constructor(keys) {
        this.data = []

        keys.forEach(key => {
            this.data.push(new Mapping(key, ''))
        })
    }

    getAll() {
        return this.data
    }
}
