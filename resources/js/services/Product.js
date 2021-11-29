import API from "./API";

    
const prefix = 'products';    

export default class {
    static async GetProducts() {
        return API.get(prefix);
    }

    static async GetCategories() {
        return API.get(`${prefix}/get-categories`);
    }

    static async GetProduct(id) {
        return API.get(`${prefix}/${id}`);
    }

    static async GetAttributes(payload) {
        return API.post(`${prefix}/get-category-attributes`, payload);
    }

    static async CreateProduct(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async UpdateProduct(payload) {
        return API.post(`${prefix}/update`, payload);
    }

    static async RemoveDiscount(payload) {
        return API.post(`${prefix}/remove-discount`, payload);
    }
}