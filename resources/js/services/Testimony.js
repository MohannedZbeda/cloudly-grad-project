import API from "./API";

    
const prefix = 'testimonies';    

export default class {
    static async GetTestimonies() {
        return API.get(prefix);
    }

    static async ChangeState(payload) {
        return API.post(`${prefix}/change-state`, payload);
    }

}