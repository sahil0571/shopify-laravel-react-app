import React from 'react'
import { Card, Frame } from '@shopify/polaris';


function NavigationLayout({ children }) {

    return (
        <Frame>
            <Card>
                <Card.Section>
                    {children}
                </Card.Section>
            </Card>
        </Frame>
    )
}

export default NavigationLayout
