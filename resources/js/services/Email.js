import API from "./API";

    
const prefix = 'emails';    

export default class {
    static async GetEmails() {
        return API.get(prefix);
    }

}