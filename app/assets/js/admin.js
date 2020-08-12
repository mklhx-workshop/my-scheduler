import React from 'react';
import ReactDOM from 'react-dom';
import { HydraAdmin } from '@api-platform/admin';

const Admin = () => <HydraAdmin entrypoint="http://localhost/api" />; // Replace with your own API entrypoint

ReactDOM.render(<Admin />, document.getElementById('api-platform-admin'));