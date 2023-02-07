import API from "./API";

    
const prefix = 'customer-requests';    

export default class {
    static async GetRequests() {
        return API.get(prefix);
    }

    static async ChangeStatus(id) {
        return API.get(`${prefix}/${id}/change-status`);
    }


}