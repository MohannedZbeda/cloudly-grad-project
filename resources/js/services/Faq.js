import API from "./API";

    
const prefix = 'faqs';    

export default class {
    static async GetFAQS() {
        return API.get(`${prefix}`);
    }

    static async GetFAQ(id) {
        return API.get(`${prefix}/${id}`);
    }

    static async CreateFAQ(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async UpdateFAQ(payload) {
        return API.post(`${prefix}/update`, payload);
    }
    static async DeleteFAQ(id) {
        return API.delete(`${prefix}/delete/${id}`);
    }

    
}