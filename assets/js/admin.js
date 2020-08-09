// import React from 'react';
// import ReactDOM from 'react-dom';
// import {
//   AdminGuesser,
//   hydraDataProvider,
//   hydraSchemaAnalyzer,
// } from '@api-platform/admin';

// const Admin = () => (
//   <AdminGuesser
//     // Use your custom data provider or resource schema analyzer
//     dataProvider={hydraDataProvider('https://127.0.0.1:8000')}
//     schemaAnalyzer={hydraSchemaAnalyzer()}
//   />
// );

// ReactDOM.render(<Admin />, document.getElementById('api-platform-admin'));

import React from 'react';
import ReactDOM from 'react-dom';
import { HydraAdmin } from '@api-platform/admin';

const Admin = () => <HydraAdmin entrypoint="https://127.0.0.1:8000/api" />; // Replace with your own API entrypoint

ReactDOM.render(<Admin />, document.getElementById('api-platform-admin'));