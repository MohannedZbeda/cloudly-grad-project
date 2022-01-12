import API from "./API";

    
const prefix = 'admins';    

export default class {
    static async GetAdmins() {
        return API.get(prefix);
    }

    static async GetRoles() {
        return API.get(`${prefix}/get-roles`);
    }

    static async GetAuthUser() {
        let response = await API.get(`/get-auth-admin`);
        return response.data.user;
    }
    static async GetUser(id) {
        return API.get(`${prefix}/admin/${id}`);
    }

    static async CreateUser(payload) {
        return API.post(prefix, payload);
    }

    static async UpdateUser(payload) {
        return API.put(prefix, payload);
    }

    static async ChangeState(payload) {
        return API.post(`${prefix}/change-state`, payload);
    }

}