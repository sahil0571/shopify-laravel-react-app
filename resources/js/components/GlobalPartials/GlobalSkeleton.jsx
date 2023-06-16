import React from 'react'

import {
    SkeletonPage,
    Layout,
    Card,
    SkeletonBodyText,
    VerticalStack,
    SkeletonDisplayText,
} from '@shopify/polaris';

function GlobalSkeleton() {
    return (
        <SkeletonPage primaryAction>
            <Layout>
                <Layout.Section>
                    <Card>
                        <SkeletonBodyText />
                    </Card>
                    <Card>
                        <VerticalStack>
                            <SkeletonDisplayText size="small" />
                            <SkeletonBodyText />
                        </VerticalStack>
                    </Card>
                    <Card>
                        <VerticalStack>
                            <SkeletonDisplayText size="small" />
                            <SkeletonBodyText />
                        </VerticalStack>
                    </Card>
                </Layout.Section>
                <Layout.Section secondary>
                    <Card>
                        <Card>
                            <VerticalStack>
                                <SkeletonDisplayText size="small" />
                                <SkeletonBodyText lines={2} />
                            </VerticalStack>
                        </Card>
                        <Card>
                            <SkeletonBodyText lines={1} />
                        </Card>
                    </Card>
                    <Card subdued>
                        <Card>
                            <VerticalStack>
                                <SkeletonDisplayText size="small" />
                                <SkeletonBodyText lines={2} />
                            </VerticalStack>
                        </Card>
                        <Card>
                            <SkeletonBodyText lines={2} />
                        </Card>
                    </Card>
                </Layout.Section>
            </Layout>
        </SkeletonPage>
    )
}

export default GlobalSkeleton
