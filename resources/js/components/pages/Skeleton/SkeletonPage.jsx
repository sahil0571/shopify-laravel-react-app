import { Page } from '@shopify/polaris'
import React from 'react'
import GlobalSkeleton from '../../GlobalPartials/GlobalSkeleton'

function SkeletonPage() {
    return (
        <Page
            title='Skeleton Page'
        >
            <GlobalSkeleton />
        </Page>
    )
}

export default SkeletonPage
