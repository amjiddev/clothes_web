<!DOCTYPE html>
<html>
<head>
    <title>API Response Debug</title>
    <style>
        body { font-family: monospace; background: #1e1e1e; color: #00ff88; padding: 20px; }
        pre { background: #0d0d0d; border: 1px solid #00ff88; padding: 15px; border-radius: 5px; overflow-x: auto; max-height: 800px; }
        button { background: #00ff88; color: #000; padding: 10px 20px; border: none; cursor: pointer; margin: 10px 0; font-weight: bold; }
        .response { background: #0d0d0d; border: 2px solid #00ff88; padding: 20px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Notification API Response Debug</h1>
    <button onclick="fetchAndDisplay()">Fetch /admin/notifications</button>
    
    <div id="output"></div>

    <script>
        async function fetchAndDisplay() {
            const output = document.getElementById('output');
            output.innerHTML = '<div class="response"><strong>Loading...</strong></div>';
            
            try {
                const response = await fetch('/admin/notifications', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                output.innerHTML = `
                    <div class="response">
                        <strong>HTTP Status: ${response.status}</strong><br>
                        <strong>Response:</strong>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                        
                        <strong>Summary:</strong>
                        <ul>
                            <li>Total notifications: ${data.notifications?.length || 0}</li>
                            <li>Orders: ${data.orders_count || 0}</li>
                            <li>Messages: ${data.messages_count || 0}</li>
                            <li>Measurements: ${data.measurements_count || 0}</li>
                            <li>Total count: ${data.unread_count || 0}</li>
                        </ul>
                        
                        <strong>Notification Types:</strong>
                        <pre>${data.notifications?.map(n => n.type).join('\n') || 'None'}</pre>
                    </div>
                `;
            } catch (error) {
                output.innerHTML = `<div class="response"><strong style="color: #ff4444;">Error: ${error.message}</strong></div>`;
            }
        }
        
        // Auto-fetch on load
        window.addEventListener('load', fetchAndDisplay);
    </script>
</body>
</html>
