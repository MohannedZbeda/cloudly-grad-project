import API from "./API";

    
const prefix = 'packages';    

export default class {
    static async GetPackages() {
        return API.get(prefix);
    }

    static async GetPackage(id) {
        return API.get(`${prefix}/${id}`);
    }
    
    static async GetProducts() {
        return API.get(`${prefix}/get-products`);
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