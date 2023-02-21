
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
    const skeletonPage = AppLink.create(app, {
        label: 'Skeleton Page',
        destination: '/skeleton-page',
    });

    // or create a NavigationMenu with the settings link active
    const navigationMenu = NavigationMenu.create(app, {
        items: [home, skeletonPage],
    });

    return (
        <></>
    )
}

export default Tabs
