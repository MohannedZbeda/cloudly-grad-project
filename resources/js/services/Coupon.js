import API from "./API";

    
const prefix = 'coupons';    

export default class {
    static async GetCoupons() {
        return API.get(`${prefix}`);
    }

    static async GetCoupon(payload) {
        return API.post(`${prefix}`, payload);
    }
    
    static async GetItems() {
      return API.get(`${prefix}/get-items`);
    }

    static async CreateCoupon(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async UpdateCoupon(payload) {
        return API.post(`${prefix}/update`, payload);
    }


}