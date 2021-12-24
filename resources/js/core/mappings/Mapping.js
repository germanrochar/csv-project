/**
 * Mapping class handles the data of a single mapping
 */
export default class Mapping {
    constructor(key, value, isCustom = false) {
        this.key = key
        this.value = value
        this.isCustom = isCustom
    }
}
