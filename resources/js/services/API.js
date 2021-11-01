import axios from 'axios'
 
      
    const API = axios.create({ 
          baseURL: '/dashboard',
          withCredentials : true,
    });

    
export default API;