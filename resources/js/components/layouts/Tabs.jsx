
import React from 'react'
import { NavigationMenu } from '@shopify/app-bridge-react';

function Tabs() {

    return (
        <>
            <NavigationMenu
                navigationLinks={[
                    {
                        label: 'Home',
                        destination: '/',
                    },
                    {
                        label: 'Skeleton Page',
                        destination: '/skeleton-page',
                    },
                    {
                        label: 'Billing',
                        destination: '/plan-page',
                    },
                ]}
            />
        </>
    )
}

export default Tabs
