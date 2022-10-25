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
     *
     * @returns array
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
     *
     * @returns array
     */
    getMappingKeys() {
        return this.data.filter((mapping) => !this.hasEmptyValue(mapping))
            .map((mapping) => mapping.key)
    }

    /**
     * Retrieves a list of mapping values
     *
     * @returns array
     */
    getMappingValues() {
        return this.data.filter((mapping) => !this.hasEmptyValue(mapping))
            .map((mapping) => this.getMappingValue(mapping))
    }

    /**
     * Calculates the value of a mapping.
     * Reason: Values can be different because are set through a vue-multiselect or a simple input text.
     *
     * @param mapping
     * @returns string
     */
    getMappingValue(mapping) {
        if (typeof mapping.value === 'object')
            return mapping.value.key

        return mapping.value
    }

    /**
     * Checks if mapping has an empty value
     *
     * @param mapping
     * @returns {boolean}
     */
    hasEmptyValue(mapping) {
        if (typeof mapping.value === 'object')
            return Object.keys(mapping).length === 0

        return mapping.value.length === 0
    }
}
