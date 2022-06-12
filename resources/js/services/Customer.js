import API from "./API";

    
const prefix = 'customers';    

export default class {
    static async GetCustomers() {
        return API.get(prefix);
    }

    static async GetCustomer(id) {
        return API.get(`${prefix}/customer/${id}`);
    }

    static async CreateCustomer(payload) {
        return API.post(prefix, payload);
    }

    static async UpdateCustomer(payload) {
        return API.put(prefix, payload);
    }

    static async ChargeWallet(payload) {
        return API.post(`${prefix}/charge-wallet`, payload);
    }

    static async ChangeState(payload) {
        return API.post(`${prefix}/change-state`, payload);
    }

}