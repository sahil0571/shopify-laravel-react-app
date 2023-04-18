import React from "react";
import {
    Routes, Route
} from "react-router-dom";

import Home from "../pages/Home/Home";
import Plan from "../pages/Plan/Plan";
import SkeletonPage from "../pages/Skeleton/SkeletonPage";

export default function RoutePath() {

    return (
        <>
            <Routes>
                <Route exact path='/' element={<Home />} />
                <Route exact path='/skeleton-page' element={<SkeletonPage />} />
                <Route exact path='/plan-page' element={<Plan/>} />
            </Routes>
        </>
    )
}

