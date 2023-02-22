
import React, { useMemo } from 'react';
import ReactDOM from 'react-dom';
import { AppProvider, Frame } from "@shopify/polaris";
import { Provider } from '@shopify/app-bridge-react';
import '@shopify/polaris/build/esm/styles.css';
import RoutePath from './components/routes/RoutePath';

import '../css/app.scss';
import { BrowserRouter, useLocation, useNavigate } from 'react-router-dom';
import Tabs from './components/layouts/Tabs';

export default function App() {
    const navigate = useNavigate();
    const location = useLocation();

    const config = {
        apiKey: __SHOPIFY_API_KEY,
        host: new URLSearchParams(location.search).get('host'),
        forceRedirect: true
    }

    const history = useMemo(
        () => ({ replace: (path) => navigate(path, { replace: true }) }),
        [navigate]
    );

    const router = useMemo(
        () => ({
            location,
            history,
        }),
        [location, history]
    );

    return (
        <AppProvider theme={{ colorScheme: "light" }}>
            <Provider
                config={config}
                router={router}
            >
                <Frame>
                    <Tabs />
                    <RoutePath />
                </Frame>
            </Provider>
        </AppProvider>
    );
}

if (document.getElementById('app')) {

    ReactDOM.render(
        <BrowserRouter>
            <App />
        </BrowserRouter>
        ,
        document.getElementById("app"));
}
