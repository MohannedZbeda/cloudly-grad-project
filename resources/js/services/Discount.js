import API from "./API";

    
const prefix = 'discounts';    

export default class {
    static async GetDiscounts() {
        return API.get(`${prefix}`);
    }

    static async GetDiscount(id) {
        return API.get(`${prefix}/${id}`);
    }
    
    static async GetItems() {
      return API.get(`${prefix}/get-items`);
    }

    static async CreateDiscount(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async UpdateDiscount(payload) {
        return API.post(`${prefix}/update`, payload);
    }


}