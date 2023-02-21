import React from 'react'
import { Page } from '@shopify/polaris';
import NavigationLayout from '../../layouts/NavigationLayout';

function Home() {
    return (
        <NavigationLayout>
            <div className='pageWrapper home'>
                <div className='pageHeading'>Laravel React App</div>
                <Page
                    compactTitle
                >

                </Page>
            </div>
        </NavigationLayout>
    )
}

export default Home
