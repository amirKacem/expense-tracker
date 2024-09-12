import "../css/app.scss"

require('bootstrap')

import React from 'react';
import { createRoot } from 'react-dom/client';
import Home from "./components/Home";
if(document.getElementById('root')) {
    const root = createRoot(document.getElementById('root'));
    root.render(<Home/>);
}
