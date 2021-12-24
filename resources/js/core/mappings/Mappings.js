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

    /**
     * Retrieves all data mappings
     */
    getAll() {
        return this.data
    }

    /**
     * Marks a mapping as custom given an index referencing the list of mappings
     * @param index
     */
    markAsCustom(index) {
        this.data[index].isCustom = true
        this.data[index].value = ''
    }

    /**
     * Marks a mapping as NOT custom given an index referencing the list of mappings
     * @param index
     */
    markAsNotCustom(index) {
        this.data[index].isCustom = false
        this.data[index].value = ''
    }

    /**
    * Retrieves a list of mapping keys
    */
    getMappingKeys() {
        let keys = []
        this.data.forEach(mapping => {
            keys.push(mapping.key)
        })

        return keys
    }

    /**
     * Retrieves a list of mapping values
     */
    getMappingValues() {
        let keys = []
        this.data.forEach(mapping => {
            if (typeof mapping.value === 'object')
                keys.push(mapping.value.key)
            else
                keys.push(mapping.value)
        })

        return keys
    }
}
