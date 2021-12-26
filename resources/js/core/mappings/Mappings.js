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
    * Retrieves a list of custom mapping keys
    */
    getCustomMappingKeys() {
        let keys = [];
        this.data.forEach(mapping => {
            if (mapping.isCustom && !this.hasEmptyValue(mapping))
                keys.push(mapping.key)
        })

        return keys
    }

    /**
     * Retrieves a list of mapping keys
     */
    getMappingKeys() {
        let keys = [];
        this.data.forEach(mapping => {
            if (!mapping.isCustom && !this.hasEmptyValue(mapping))
                keys.push(mapping.key)
        })

        return keys
    }

    /**
     * Retrieves a list of mapping values
     */
    getMappingValues() {
        let values = [];
        this.data.forEach(mapping => {
            if (!mapping.isCustom && !this.hasEmptyValue(mapping))
                values.push(this.getMappingValue(mapping))
        })

        return values
    }

    /**
     * Retrieves a list of custom mapping values
     */
    getCustomMappingValues() {
        let values = [];
        this.data.forEach(mapping => {
            if (mapping.isCustom && !this.hasEmptyValue(mapping))
                values.push(this.getMappingValue(mapping))
        })

        return values
    }

    /**
     * Calculates the value of a mapping.
     * Reason: Values can be different because are set through a vue-multiselect or a simple input text.
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
