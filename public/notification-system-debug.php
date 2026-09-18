<!DOCTYPE html>
<html>
<head>
    <title>Notification System Debug</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Courier New', monospace; 
            background: linear-gradient(135deg, #0a0e27 0%, #1a1a3a 100%);
            color: #00ff88;
            padding: 20px;
            min-height: 100vh;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #00ffff; margin-bottom: 20px; text-shadow: 0 0 10px #00ffff; }
        h2 { color: #ffaa00; margin: 20px 0 10px; border-bottom: 2px solid #ffaa00; padding-bottom: 5px; }
        .section { 
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid #00ff88;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
        }
        .status { 
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            margin-left: 10px;
        }
        .status.ok { background: #00ff88; color: #000; }
        .status.error { background: #ff4444; color: #fff; }
        .status.warn { background: #ffaa00; color: #000; }
        .status.info { background: #00ffff; color: #000; }
        .test-button {
            background: #00ff88;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-family: monospace;
            margin: 5px;
            transition: all 0.3s;
        }
        .test-button:hover { background: #00ffff; transform: translateY(-2px); }
        .test-button:active { transform: translateY(0); }
        .output {
            background: #000;
            border: 1px solid #00ff88;
            padding: 10px;
            border-radius: 3px;
            margin-top: 10px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 0.9em;
        }
        .output.error { border-color: #ff4444; }
        .output.success { border-color: #00ff88; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { 
            border: 1px solid #00ff88;
            padding: 8px;
            text-align: left;
        }
        th { background: rgba(0, 255, 136, 0.2); }
        tr:nth-child(even) { background: rgba(0, 0, 0, 0.2); }
        .check-item { margin: 8px 0; }
        .check-item.pass { color: #00ff88; }
        .check-item.fail { color: #ff4444; }
        .check-item.warn { color: #ffaa00; }
        .collapse-btn { 
            background: #00ff88;
            color: #000;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 3px;
        }
        .collapsed { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔔 Notification System Debugger</h1>
        
        <!-- System Check -->
        <div class="section">
            <h2>System Status Check</h2>
            <div id="systemCheck"></div>
        </div>
        
        <!-- API Tests -->
        <div class="section">
            <h2>API Endpoint Tests</h2>
            <div>
                <button class="test-button" onclick="testApiEndpoint('/admin/notifications/unread-count', 'GET')">Test: GET /admin/notifications/unread-count</button>
                <button class="test-button" onclick="testApiEndpoint('/admin/notifications', 'GET')">Test: GET /admin/notifications</button>
                <button class="test-button" onclick="testApiEndpoint('/receptionist/notifications/unread-count', 'GET')">Test: GET /receptionist/notifications/unread-count</button>
            </div>
            <div id="apiOutput" class="output" style="display:none;"></div>
        </div>
        
        <!-- Network Check -->
        <div class="section">
            <h2>Network & CSRF Check</h2>
            <button class="test-button" onclick="checkNetworkAndCSRF()">Check CSRF & Headers</button>
            <div id="networkOutput" class="output" style="display:none;"></div>
        </div>
        
        <!-- Database Check -->
        <div class="section">
            <h2>Database Schema Check</h2>
            <button class="test-button" onclick="checkDatabaseSchema()">Check Database Tables</button>
            <div id="dbOutput" class="output" style="display:none;"></div>
        </div>
        
        <!-- Full System Report -->
        <div class="section">
            <h2>Full System Report</h2>
            <button class="test-button" onclick="generateFullReport()">Generate Full Report</button>
            <div id="reportOutput" class="output" style="display:none;"></div>
        </div>
    </div>

    <script>
        // Initialize system check on load
        document.addEventListener('DOMContentLoaded', function() {
            performInitialChecks();
        });

        function log(id, message, type = 'info') {
            const el = document.getElementById(id);
            if (!el) return;
            
            const timestamp = new Date().toLocaleTimeString();
            const prefix = type === 'error' ? '❌' : type === 'success' ? '✅' : type === 'warn' ? '⚠️' : 'ℹ️';
            const line = `[${timestamp}] ${prefix} ${message}\n`;
            
            el.textContent += line;
            el.scrollTop = el.scrollHeight;
        }

        function performInitialChecks() {
            const check = document.getElementById('systemCheck');
            let html = '';
            
            // Check 1: Current user path
            const path = window.location.pathname;
            const isAdmin = path.includes('/admin/');
            const isReceptionist = path.includes('/receptionist/');
            
            html += `<div class="check-item ${isAdmin || isReceptionist ? 'pass' : 'fail'}">
                📍 Current Path: ${path}<br>
                User Role: ${isAdmin ? 'ADMIN' : isReceptionist ? 'RECEPTIONIST' : 'UNKNOWN'}
            </div>`;
            
            // Check 2: CSRF Token
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            html += `<div class="check-item ${csrf ? 'pass' : 'fail'}">
                🔐 CSRF Token: ${csrf ? '✅ Found' : '❌ NOT FOUND'}
            </div>`;
            
            // Check 3: Bootstrap Check
            const hasBootstrap = typeof bootstrap !== 'undefined' || window.jQuery?.fn?.modal;
            html += `<div class="check-item ${hasBootstrap ? 'pass' : 'fail'}">
                📦 Bootstrap/jQuery: ${hasBootstrap ? '✅ Loaded' : '⚠️  May not be available'}
            </div>`;
            
            // Check 4: Notification Bell Element
            const bell = document.getElementById('notificationBellButton');
            html += `<div class="check-item ${bell ? 'pass' : 'fail'}">
                🔔 Notification Bell Element: ${bell ? '✅ Found' : '❌ NOT FOUND'}
            </div>`;
            
            // Check 5: Font Awesome
            const hasFontAwesome = document.querySelector('link[href*="font-awesome"]') || 
                                  document.querySelector('script[src*="font-awesome"]') ||
                                  window.getComputedStyle(document.documentElement).getPropertyValue('--fa-font-weight');
            html += `<div class="check-item ${hasFontAwesome ? 'pass' : 'warn'}">
                🎨 Font Awesome: ${hasFontAwesome ? '✅ Loaded' : '⚠️  May not be loaded'}
            </div>`;
            
            check.innerHTML = html;
        }

        async function testApiEndpoint(endpoint, method = 'GET') {
            const output = document.getElementById('apiOutput');
            output.style.display = 'block';
            output.textContent = '';
            output.classList.remove('error', 'success');
            
            log('apiOutput', `Testing: ${method} ${endpoint}`, 'info');
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                log('apiOutput', `HTTP Status: ${response.status} ${response.statusText}`, 
                    response.ok ? 'success' : 'error');
                
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    log('apiOutput', `Response: ${JSON.stringify(data, null, 2)}`, response.ok ? 'success' : 'error');
                } else {
                    const text = await response.text();
                    log('apiOutput', `Response (${contentType}): ${text.substring(0, 200)}`, response.ok ? 'success' : 'error');
                }
                
                output.classList.add(response.ok ? 'success' : 'error');
            } catch (error) {
                log('apiOutput', `Error: ${error.message}`, 'error');
                output.classList.add('error');
            }
        }

        async function checkNetworkAndCSRF() {
            const output = document.getElementById('networkOutput');
            output.style.display = 'block';
            output.textContent = '';
            output.classList.remove('error', 'success');
            
            log('networkOutput', 'Checking Network & CSRF...', 'info');
            
            // CSRF Token
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta?.getAttribute('content');
            
            log('networkOutput', `CSRF Token Meta Tag: ${csrfMeta ? '✅ EXISTS' : '❌ NOT FOUND'}`, csrfMeta ? 'success' : 'error');
            log('networkOutput', `CSRF Token Value: ${csrf ? csrf.substring(0, 20) + '...' : 'EMPTY'}`, csrf ? 'success' : 'error');
            
            // Headers check
            log('networkOutput', `User Agent: ${navigator.userAgent}`, 'info');
            log('networkOutput', `Current URL: ${window.location.href}`, 'info');
            
            // Try a simple fetch to check network
            try {
                const response = await fetch(window.location.href, { 
                    method: 'HEAD',
                    headers: {
                        'X-CSRF-TOKEN': csrf || '',
                    }
                });
                log('networkOutput', `Network: ✅ Available (Status: ${response.status})`, 'success');
            } catch (e) {
                log('networkOutput', `Network: ❌ Error - ${e.message}`, 'error');
            }
            
            output.classList.add('success');
        }

        async function checkDatabaseSchema() {
            const output = document.getElementById('dbOutput');
            output.style.display = 'block';
            output.textContent = '';
            
            log('dbOutput', 'Checking database schema...', 'info');
            log('dbOutput', 'Loading schema information from server...', 'info');
            
            try {
                const response = await fetch('/cloth/public/check-db-schema.php');
                const html = await response.text();
                
                // Extract table info from HTML
                const tableMatches = html.match(/<h3>Table:.*?<\/table>/gs);
                if (tableMatches) {
                    log('dbOutput', `Found ${tableMatches.length} database tables`, 'success');
                    tableMatches.forEach(match => {
                        if (match.includes('is_seen') || match.includes('is_read')) {
                            log('dbOutput', 'Notification columns found in table', 'success');
                        }
                    });
                } else {
                    log('dbOutput', 'Could not parse database schema page', 'warn');
                }
                
                output.classList.add('success');
            } catch (error) {
                log('dbOutput', `Error checking database: ${error.message}`, 'error');
                log('dbOutput', 'Try visiting /cloth/public/check-db-schema.php directly', 'info');
                output.classList.add('error');
            }
        }

        async function generateFullReport() {
            const output = document.getElementById('reportOutput');
            output.style.display = 'block';
            output.textContent = '';
            
            log('reportOutput', '=== FULL SYSTEM REPORT ===', 'info');
            log('reportOutput', `Generated: ${new Date().toLocaleString()}`, 'info');
            log('reportOutput', '', 'info');
            
            // Browser Info
            log('reportOutput', '--- BROWSER INFO ---', 'info');
            log('reportOutput', `Browser: ${navigator.userAgent}`, 'info');
            log('reportOutput', `Platform: ${navigator.platform}`, 'info');
            
            // Page Info
            log('reportOutput', '', 'info');
            log('reportOutput', '--- PAGE INFO ---', 'info');
            log('reportOutput', `URL: ${window.location.href}`, 'info');
            log('reportOutput', `Path: ${window.location.pathname}`, 'info');
            
            // Check all endpoints
            log('reportOutput', '', 'info');
            log('reportOutput', '--- API ENDPOINTS ---', 'info');
            
            const endpoints = [
                '/admin/notifications/unread-count',
                '/admin/notifications',
                '/receptionist/notifications/unread-count',
                '/receptionist/notifications'
            ];
            
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            for (const endpoint of endpoints) {
                try {
                    const response = await fetch(endpoint, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        }
                    });
                    log('reportOutput', `${endpoint}: ${response.status} ${response.ok ? '✅' : '❌'}`, response.ok ? 'success' : 'error');
                } catch (e) {
                    log('reportOutput', `${endpoint}: ERROR - ${e.message}`, 'error');
                }
            }
            
            log('reportOutput', '', 'info');
            log('reportOutput', '=== END REPORT ===', 'info');
            output.classList.add('success');
        }
    </script>
</body>
</html>
