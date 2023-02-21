import axios from "axios";
import { getSessionToken } from "@shopify/app-bridge-utils";

const instance = axios.create();
// Here window.shopify_app_bridge is App.

instance.interceptors.request.use(async function (config) {
    return await getSessionToken(window.shopify_app_bridge)
        .then((token) => {
            // console.log('BEAREE :: ', token);
            config.headers["Authorization"] = `Bearer ${token}`;
            return config;
        });
});

// Export your Axios instance to use within your app
export default instance;
