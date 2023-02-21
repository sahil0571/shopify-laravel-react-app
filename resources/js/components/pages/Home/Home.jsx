import React from 'react'
import { Page, Text } from '@shopify/polaris';

function Home() {
    return (
        <Page
            title='Home Page'
        >
            <Text> Hello {window.shopOrigin} </Text>
        </Page>
    )
}

export default Home
