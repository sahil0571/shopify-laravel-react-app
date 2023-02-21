
import React, { useMemo } from "react";
import {
    Routes, Route
} from "react-router-dom";

import Home from "../pages/Home/Home";
import { useNavigate } from 'react-router-dom';
import { ClientRouter } from '@shopify/app-bridge-react/components/ClientRouter';

export default function RoutePath() {

    const navigate = useNavigate();
    const history = useMemo(
        () => ({ replace: (path) => navigate(path, { replace: true }) }),
        [navigate],
    );
    return (
        <>
            {/* <ClientRouter history={history} /> */}
            <Routes>
                <Route exact path='/' element={<Home />} />
                {/* <Route exact path='/admin/widget-styling' element={<WidgetStyling />} /> */}
            </Routes>
        </>
    )
}

