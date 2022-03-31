import API from "./API";

const prefix = "cycles";

export default class {
    static async GetCycles() {
        return API.get(prefix);
    }

    static async AllCycles() {
        return API.get(`${prefix}/get-cycles`);
    }

    static async CreateCycle(payload) {
        return API.post(`${prefix}/store`, payload);
    }

    static async ChangeState(payload) {
        return API.post(`${prefix}/change-state`, payload);
    }

    static async UpdateDiscount(payload) {
        return API.post(`${prefix}/update-discount`, payload);
    }
}
