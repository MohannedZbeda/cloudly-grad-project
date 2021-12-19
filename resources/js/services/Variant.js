import API from "./API";

    
const prefix = 'variants';    

export default class {
    static async GetVariants(id) {
        return API.get(`${prefix}/${id}`);
    }

    static async GetProducts() {
        return API.get(`${prefix}/get-products`);
    }

    static async GetVariant(id) {
        return API.get(`${prefix}/variant/${id}`);
    }

    static async GetAttributes(payload) {
        return API.post(`${prefix}/get-product-attributes`, payload);
    }

    static async CreateVariant(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async UpdateVariant(payload) {
        return API.post(`${prefix}/update`, payload);
    }

    static async RemoveDiscount(payload) {
        return API.post(`${prefix}/remove-discount`, payload);
    }
    static async AddVouchers(payload) {
        return API.post(`${prefix}/add-vouchers`, payload);
    }
}