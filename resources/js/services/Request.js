import API from "./API";

    
const prefix = 'customer-requests';    

export default class {
    static async GetRequests() {
        return API.get(prefix);
    }

    static async ChangeState() {
        return API.get(`${prefix}/change-state`);
    }


}