import React from 'react';
import ReactDOM from 'react-dom/client';
import DarkVeil from './components/DarkVeil';
import './styles/dashboard.css';

const container = document.getElementById('react-dark-veil');

if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(
    <React.StrictMode>
      <DarkVeil />
    </React.StrictMode>
  );
}

window.addEventListener('beforeunload', function (e) {
    // Use navigator.sendBeacon to send a request to the logout URL
    // This is more reliable than fetch or XMLHttpRequest for requests during unload
    if (navigator.sendBeacon) {
        navigator.sendBeacon('/logout');
    } else {
        // Fallback for older browsers
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/logout', false); // synchronous request
        xhr.send();
    }
});