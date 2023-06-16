import axios from "axios";
import { getSessionToken } from "@shopify/app-bridge/utilities";
import { createApp } from "@shopify/app-bridge";

const instance = axios.create();

const shopifConfig = {
    apiKey: __SHOPIFY_API_KEY,
    host: new URLSearchParams(location.search).get('host'),
    forceRedirect: true
}

const app = createApp(shopifConfig);

instance.interceptors.request.use(function (config) {
    return getSessionToken(app) // requires a Shopify App Bridge instance
        .then((token) => {
            // Append your request headers with an authenticated token
            config.headers.Authorization = `Bearer ${token}`
            return config
        })
})

// Export your Axios instance to use within your app
export default instance;
