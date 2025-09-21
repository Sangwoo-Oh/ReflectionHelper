import React from 'react';
import ReactDOM from 'react-dom/client';
import Datepicker from './components/Datepicker';

const components = {
    'Datepicker': Datepicker,
}

const rootElements = document.querySelectorAll('.react-root');
rootElements.forEach(el => {
    const componentName = el.dataset.components;
    const Component = components[componentName];
    if (!Component) {
        console.error(`Component "${componentName}" not found.`);
        return;
    }
    const props = JSON.parse(el.dataset.props || '{}');
    
    const root = ReactDOM.createRoot(el);
    root.render(<Component {...props} />);
});