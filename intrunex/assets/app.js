import React from 'react';
import ReactDOM from 'react-dom/client';
import DarkVeil from './components/DarkVeil';

const container = document.getElementById('react-dark-veil');

if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(
    <React.StrictMode>
      <div style={{ width: '100%', height: '600px', position: 'relative' }}>
        <DarkVeil />
      </div>
    </React.StrictMode>
  );
}
