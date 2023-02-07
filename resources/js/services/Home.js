import API from "./API";
    
const prefix = 'home-data';    

export default class {
    static async GetData() {
        return API.get(prefix);
    }

}