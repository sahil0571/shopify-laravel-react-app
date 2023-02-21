
import React from 'react'

import createApp from '@shopify/app-bridge';
import { AppLink, NavigationMenu } from '@shopify/app-bridge/actions';
const config = window.shopify_app_bridge_config;
const app = createApp(config);

function Tabs() {

    const home = AppLink.create(app, {
        label: 'Home',
        destination: '/',
    });

    // or create a NavigationMenu with the settings link active
    const navigationMenu = NavigationMenu.create(app, {
        items: [home],
    });

    return (
        <></>
    )
}

export default Tabs
