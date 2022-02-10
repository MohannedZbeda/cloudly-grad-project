import API from "./API";

var prefix;
    

export default class {
    constructor(attribute_id) {
      prefix = `categories/attributes/${attribute_id}/values`
    }
    async CreateValue(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    async ChangeValueState(payload) {
        return API.post(`${prefix}/change-state`, payload);
    }
    

}