
import React from 'react';
import ReactDOM from 'react-dom';
import { AppProvider, Frame } from "@shopify/polaris";
import en from '@shopify/polaris/locales/en.json';
import { Provider } from '@shopify/app-bridge-react';
const config = window.shopify_app_bridge_config;
import '@shopify/polaris/build/esm/styles.css'
import RoutePath from './components/routes';

import '../css/app.scss'
import { BrowserRouter } from 'react-router-dom';
import Tabs from './components/layouts/Tabs';

export default function App() {
    return (
        <div className="App">
            <div className='layoutSpace'>
                <Frame>
                    <Tabs />
                    <BrowserRouter>
                        <RoutePath />
                    </BrowserRouter>
                </Frame>
            </div>
        </div>
    );
}

if (document.getElementById('app')) {

    ReactDOM.render(
        <Provider
            config={config}
        >
            <AppProvider i18n={en} theme={{ colorScheme: "light" }}>
                <App />
            </AppProvider>
        </Provider>,
        document.getElementById("app"));
}
