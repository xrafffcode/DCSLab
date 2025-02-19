import axios from "axios";

const defaultAxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-LogRequestResponse': 'false',
        'X-Sanitizer-Mode': ''
    }
});

defaultAxiosInstance.defaults.withCredentials = true;
defaultAxiosInstance.defaults.withXSRFToken = true;

defaultAxiosInstance.interceptors.request.use(function (config) {
    config.headers['X-Localization'] = localStorage.getItem('DCSLAB_LANG') == null ? document.documentElement.lang : localStorage.getItem('DCSLAB_LANG');
    return config;
});

defaultAxiosInstance.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response == undefined || error.response.status == undefined) return Promise.reject(error);
    switch (error.response.status) {
        case 401:
            window.location.replace('/auth/login');
            break;
        case 403:
            break;
        case 500:
            break;
        default:
            break;
    }
    return Promise.reject(error);
});

const authAxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
    }
});

authAxiosInstance.defaults.withCredentials = true;

const axiosInstance = axios.create();

export { defaultAxiosInstance as default, authAxiosInstance, axiosInstance }