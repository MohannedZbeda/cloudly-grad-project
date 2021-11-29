import API from "./API";

    
const prefix = 'categories';    

export default class {
    static async GetCategories() {
        return API.get(prefix);
    }

    static async GetCategory(id) {
        return API.get(`${prefix}/${id}`);
    }

    static async CreateCategory(payload) {
        return API.post(`${prefix}/store`, payload);
    }
    static async UpdateCategory(payload) {
        return API.post(`${prefix}/update`, payload);
    }

    static async GetAttributes(id) {
        return API.get(`${prefix}/${id}/attributes`);
    }


    static async GetAttribute(category_id, id) {
        return API.get(`${prefix}/${category_id}/attributes/${id}`);
    }

    static async AddAttribute(payload) {
        return API.post(`${prefix}/attributes/store`, payload);
    }

    static async UpdateAttribute(payload) {
        return API.post(`${prefix}/attributes/update`, payload);
    }
    

    static async GetValues(attribute_id) {
        return API.get(`${prefix}/attributes/${attribute_id}/values`);
    }
    
    static async AddValue(id, payload) {
        return API.post(`${prefix}/attributes/${id}/values/store`, payload);
    }

    static async UpdateValue(id, payload) {
        return API.post(`${prefix}/attributes/${id}/values/update`, payload);
    }

}