import Option from '../components/Option';

/**
 * This class handles mapping options in MappingFieldsPage.vue
 */
export default class ContactMappingOptions {
    constructor(data) {
        this.data = []
        this.data.push(new Option('custom', 'Add a custom field'))

        data.forEach(key => {
            this.data.push(new Option(key, key))
        })
    }

    getAll() {
        return this.data
    }
}
