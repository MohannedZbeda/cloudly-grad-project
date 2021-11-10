import API from "./API";

    
const prefix = 'categories';    

export default class {
    static async GetCategories() {
        return API.get(prefix);
    }

    static async GetCategory(id) {
        return API.get(prefix + '/' + id);
    }

    
    static async GetUser(id) {
        return API.get(prefix + '/admin/' + id);
    }

    static async CreateCategory(payload) {
        return API.post(prefix + '/store', payload);
    }
    static async UpdateCategory(payload) {
        return API.post(prefix + '/update', payload);
    }

    static async UpdateUser(payload) {
        return API.put(prefix, payload);
    }

    static async ChangeState(payload) {
        return API.post(prefix + '/change-state', payload);
    }

}