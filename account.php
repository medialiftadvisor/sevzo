<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My SEVZO Account</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #3b0068; 
            --secondary: #ff005c; 
            --bg: #f8fafc; 
            --white: #ffffff;
            --dark: #0f172a;
            --gray-light: #f1f5f9;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: var(--bg); display: flex; justify-content: center; color: var(--dark); padding-bottom: 90px; }
        
        .app-container { width: 100%; max-width: 480px; background: white; min-height: 100vh; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        /* Auth Section */
        #auth-section { padding: 50px 25px; text-align: center; }
        .auth-logo { font-size: 32px; font-weight: 900; color: var(--primary); margin-bottom: 10px; }
        .auth-logo span { color: var(--secondary); }
        .auth-card { background: white; padding: 30px 25px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); margin-top: 20px; border: 1.5px solid var(--gray-light); }
        .input-ctrl { width: 100%; padding: 14px 18px; border: 1.5px solid var(--gray-light); border-radius: 14px; margin-bottom: 12px; outline: none; font-size: 14px; font-weight: 600; transition: 0.3s; background: #f8fafc; }
        .input-ctrl:focus { border-color: var(--primary); background: white; box-shadow: 0 8px 20px rgba(59, 0, 104, 0.05); }
        .btn-auth { width: 100%; padding: 15px; background: var(--primary); color: white; border: none; border-radius: 14px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.3s; box-shadow: 0 6px 15px rgba(59, 0, 104, 0.15); }
        .btn-auth:active { transform: scale(0.96); }
        
        /* Profile Header */
        .profile-header { background: linear-gradient(135deg, var(--primary), #22003c); color: white; padding: 45px 20px; text-align: center; border-bottom-left-radius: 30px; border-bottom-right-radius: 30px; }
        .user-avatar { width: 75px; height: 75px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 12px; border: 2.5px solid var(--secondary); font-weight: 900; color: white; }
        .u-title { font-size: 20px; font-weight: 800; }
        .u-phone { opacity: 0.8; font-size: 14px; font-weight: 500; margin-top: 2px; }
        
        /* Tabs */
        .tabs { display: flex; background: white; border-bottom: 1px solid var(--gray-light); position: sticky; top: 0; z-index: 100; }
        .tab-link { flex: 1; padding: 16px; text-align: center; font-size: 13px; font-weight: 800; color: #94a3b8; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; transition: 0.2s; }
        .tab-link.active { color: var(--secondary); border-bottom: 3.5px solid var(--secondary); }
        .section { padding: 20px; display: none; }
        .section.active { display: block; }
 
        /* Order Tracking Overlay */
        .tracking-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: white; z-index: 2000; display: none; flex-direction: column; }
        .track-header { padding: 18px 20px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid var(--gray-light); background: white; font-weight: 800; color: var(--primary); }
        .track-header i { font-size: 18px; cursor: pointer; }
        
        /* Stepper */
        .stepper { position: relative; padding-left: 32px; margin-top: 25px; }
        .step { position: relative; padding-bottom: 32px; font-size: 14px; font-weight: 600; color: #64748b; }
        .step b { color: var(--dark); font-size: 15px; font-weight: 800; }
        .step::before { content: ''; position: absolute; left: -24px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: #e2e8f0; z-index: 2; border: 2px solid white; transition: 0.3s; }
        .step::after { content: ''; position: absolute; left: -18px; top: 16px; width: 2px; height: 100%; background: #f1f5f9; z-index: 1; }
        .step.completed::before { background: #10b981; }
        .step.completed { color: #10b981; }
        .step.active::before { background: var(--secondary); box-shadow: 0 0 10px rgba(255, 0, 92, 0.4); }
        .step.active { color: var(--secondary); }
        .step:last-child::after { display: none; }
 
        /* Bottom Navigation */
        .bottom-nav { position: fixed; bottom: 0; width: 100%; max-width: 480px; background: rgba(255,255,255,0.92); backdrop-filter: blur(15px); display: flex; justify-content: space-around; padding: 10px 10px 14px; border-top: 1px solid var(--gray-light); z-index: 1000; border-radius: 20px 20px 0 0; }
        .nav-item { text-align: center; color: #94a3b8; text-decoration: none; font-size: 11px; font-weight: 700; flex: 1; }
        .nav-item.active { color: var(--primary); }
        .nav-item i { font-size: 22px; margin-bottom: 3px; display: block; }
        .nav-item.active i { transform: translateY(-2px); }

        /* Wallet custom */
        .wallet-card { background: #fff0f5; padding: 30px; border-radius: 24px; border: 1.5px dashed var(--secondary); text-align: center; box-shadow: 0 8px 25px rgba(255, 0, 92, 0.03); }
        .wallet-amt { color: var(--primary); font-size: 40px; font-weight: 900; margin-top: 8px; }
        
        .recharge-section { margin-top: 25px; background: white; padding: 20px; border-radius: 20px; border: 1.5px solid var(--gray-light); }
        .recharge-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px; }
        .btn-chip { background: #f8fafc; border: 1.5px solid var(--gray-light); padding: 10px; border-radius: 10px; font-weight: 800; font-size: 13px; cursor: pointer; transition: 0.2s; text-align: center; }
        .btn-chip:active { transform: scale(0.95); background: var(--primary); color: white; border-color: var(--primary); }

        /* Address styles */
        .btn-gps { width: 100%; padding: 14px; background: #eef2ff; border: 1.5px solid #c7d2fe; color: #4338ca; border-radius: 12px; margin-bottom: 15px; font-size: 13px; font-weight: 800; cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-gps:active { transform: scale(0.97); }

        .order-card-wrap { background: white; border: 1.5px solid var(--gray-light); padding: 18px; border-radius: 20px; margin-bottom: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.01); transition: 0.3s; position: relative; }
        .order-card-wrap:hover { border-color: #cbd5e1; }
    </style>
</head>
<body>
 
<div class="app-container">
     
    <!-- Auth Section -->
    <div id="auth-section" style="display: none;">
        <div class="auth-logo">SEV<span>ZO</span></div>
        <h2 id="auth-title" style="font-weight:900;">Login to SEVZO Mall</h2>
        <p style="color:#64748b; font-size:13px; margin-top:5px; font-weight:600;">Experience lightning fast 15-minute delivery</p>
        <div class="auth-card">
            <input type="text" id="auth-name" class="input-ctrl" placeholder="Full Name" style="display:none;">
            <input type="number" id="auth-phone" class="input-ctrl" placeholder="Mobile Number">
            <input type="password" id="auth-pass" class="input-ctrl" placeholder="Password">
            <button onclick="submitAuth()" id="auth-btn" class="btn-auth">Login</button>
            <p id="toggle-text" style="margin-top:20px; font-size:13px; color:#64748b; font-weight:600; cursor:pointer;" onclick="toggleAuth()">Don't have an account? <b style="color:var(--secondary)">Sign Up</b></p>
        </div>
    </div>
 
    <!-- Dashboard Profile Section -->
    <div id="profile-content" style="display: none;">
        <div class="profile-header">
            <div class="user-avatar" id="u-initials">U</div>
            <h2 id="u-display-name" class="u-title">User Name</h2>
            <p id="u-display-phone" class="u-phone">+91 0000000000</p>
        </div>
 
        <div class="tabs">
            <div class="tab-link active" id="tab-link-orders" onclick="switchTab('tab-orders', this); loadOrders();">Orders</div>
            <div class="tab-link" id="tab-link-wallet" onclick="switchTab('tab-wallet', this); syncWallet();">Wallet</div>
            <div class="tab-link" id="tab-link-address" onclick="switchTab('tab-address', this); fetchDatabaseAddress();">Address</div>
        </div>
 
        <!-- Orders Tab -->
        <div id="tab-orders" class="section active">
            <div id="orders-list"><p style="text-align:center; padding:20px; color:#94a3b8; font-weight:600;">Loading orders...</p></div>
        </div>
 
        <!-- Wallet Tab -->
        <div id="tab-wallet" class="section">
            <div class="wallet-card">
                <p style="color: #64748b; font-size: 13px; font-weight: 700; text-transform: uppercase;">SEVZO Wallet Balance</p>
                <h1 class="wallet-amt">₹<span id="u-wallet-amt">0.00</span></h1>
            </div>
            
            <div class="recharge-section">
                <h4 style="font-weight: 800; font-size: 14px; color: var(--primary);">SIMULATED WALLET RECHARGE</h4>
                <p style="font-size:11px; color:#64748b; margin-top:2px;">Recharge instantly to order from SEVZO</p>
                <input type="number" id="recharge-val" class="input-ctrl" placeholder="Enter amount in ₹" style="margin-top:12px; margin-bottom:10px;">
                <div class="recharge-grid">
                    <button class="btn-chip" onclick="setRecharge(100)">+ ₹100</button>
                    <button class="btn-chip" onclick="setRecharge(200)">+ ₹200</button>
                    <button class="btn-chip" onclick="setRecharge(500)">+ ₹500</button>
                </div>
                <button onclick="handleRecharge()" class="btn-auth" style="margin-top:15px; background:var(--secondary); box-shadow:0 6px 15px rgba(255,0,92,0.2);">Add Money to Wallet</button>
            </div>

            <button onclick="logout()" style="width:100%; margin-top:30px; padding:15px; color:#ef4444; border:1.5px solid var(--gray-light); background:none; border-radius:14px; font-weight:800; cursor:pointer; font-size:13px; transition:0.2s;">LOGOUT SEVZO ACCOUNT</button>
        </div>
 
        <!-- Address Tab -->
        <div id="tab-address" class="section">
            <h3 style="font-weight:800; font-size: 16px; margin-bottom:15px; color:var(--primary);">Delivery Address Details</h3>
            <label style="font-size:11px; color:#64748b; font-weight:700;">RECEIVER NAME</label>
            <input type="text" id="addr-name" class="input-ctrl" placeholder="Receiver's Full Name">
            
            <label style="font-size:11px; color:#64748b; font-weight:700;">EMAIL ID (OPTIONAL)</label>
            <input type="email" id="addr-email" class="input-ctrl" placeholder="Gmail ID">
            
            <div style="display:flex; gap:10px;">
                <div style="flex:1;">
                    <label style="font-size:11px; color:#64748b; font-weight:700;">PINCODE</label>
                    <input type="number" id="addr-pin" class="input-ctrl" placeholder="Pincode (e.g. 302001)">
                </div>
                <div style="flex:1;">
                    <label style="font-size:11px; color:#64748b; font-weight:700;">CITY</label>
                    <input type="text" id="addr-city" class="input-ctrl" placeholder="City (e.g. Jaipur)">
                </div>
            </div>
            
            <label style="font-size:11px; color:#64748b; font-weight:700;">HOUSE NO. / BUILDING / FLOOR</label>
            <input type="text" id="addr-house" class="input-ctrl" placeholder="House No. 34-A, Apex Tower">
            
            <label style="font-size:11px; color:#64748b; font-weight:700;">FULL ADDRESS</label>
            <input type="text" id="addr-full" class="input-ctrl" placeholder="Full Street details, Area name">
            
            <label style="font-size:11px; color:#64748b; font-weight:700;">LANDMARK</label>
            <input type="text" id="addr-mark" class="input-ctrl" placeholder="Near Apex School">
            
            <label style="font-size:11px; color:#64748b; font-weight:700;">GPS COORDINATES</label>
            <button onclick="fetchGPS()" id="gps-btn" class="btn-gps">
                <i class="fas fa-map-marker-alt"></i> Capture Current GPS Geolocation
            </button>
            <input type="hidden" id="addr-lat"><input type="hidden" id="addr-lng">
            
            <button onclick="saveCompleteAddress()" class="btn-auth" style="background:var(--secondary); box-shadow:0 6px 15px rgba(255,0,92,0.2); font-size:14px;">Save Address Details</button>
        </div>
    </div>
 
    <!-- Order Tracking View -->
    <div id="tracking-view" class="tracking-overlay">
        <div class="track-header">
            <i class="fas fa-arrow-left" onclick="closeTracking()"></i>
            <b>Order Tracking #SEVZO-<span id="track-id">0</span></b>
        </div>
        
        <!-- Live Google Map Iframe -->
        <div id="map-container" style="display:none; height:260px; border-bottom:1.5px solid var(--gray-light);">
            <iframe id="map-frame" width="100%" height="100%" frameborder="0" style="border:0;"></iframe>
            <div style="background:#fffbeb; padding:10px; font-size:12px; font-weight:600; text-align:center; color:#b45309;"><i class="fas fa-motorcycle"></i> SEVZO Delivery Partner is navigating to your address!</div>
        </div>
 
        <div style="padding: 20px 25px;">
            <h3 style="font-weight: 800; font-size: 16px; margin-bottom: 15px;">Delivery Timeline</h3>
            <div class="stepper">
                <div class="step" id="step-placed"><b>Order Placed</b><br><small id="track-time">Evaluating time...</small></div>
                <div class="step" id="step-confirmed"><b>Order Confirmed</b><br><small>Inventory packed and verified</small></div>
                <div class="step" id="step-out"><b>Out for Delivery</b><br><small>Rider is carrying your package</small></div>
                <div class="step" id="step-delivered"><b>Delivered</b><br><small>Order received successfully</small></div>
            </div>
        </div>
    </div>
 
    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="index.html" class="nav-item"><i class="fas fa-home"></i>Home</a>
        <a href="cart.php" class="nav-item"><i class="fas fa-shopping-basket"></i>Cart</a>
        <a href="#" class="nav-item active"><i class="fas fa-user-alt"></i>Account</a>
    </div>
</div>
 
<script>
    let isLoginMode = true;
    let user = JSON.parse(localStorage.getItem('quickmart_user'));
 
    window.onload = () => {
        // Check URL parameters for active tab
        const urlParams = new URLSearchParams(window.location.search);
        const urlTab = urlParams.get('tab');

        if(!user) { 
            document.getElementById('auth-section').style.display = 'block'; 
        } else { 
            initDashboard(); 
            if(urlTab === 'wallet') {
                const wLink = document.getElementById('tab-link-wallet');
                switchTab('tab-wallet', wLink);
            } else if (urlTab === 'orders') {
                const oLink = document.getElementById('tab-link-orders');
                switchTab('tab-orders', oLink);
            }
        }
    };
 
    function toggleAuth() {
        isLoginMode = !isLoginMode;
        document.getElementById('auth-title').innerText = isLoginMode ? "Login to SEVZO Mall" : "Create SEVZO Account";
        document.getElementById('auth-name').style.display = isLoginMode ? "none" : "block";
        document.getElementById('auth-btn').innerText = isLoginMode ? "Login" : "Register";
        document.getElementById('toggle-text').innerHTML = isLoginMode ? "Don't have an account? <b style='color:var(--secondary)'>Sign Up</b>" : "Already have an account? <b style='color:var(--secondary)'>Login</b>";
    }
 
    async function submitAuth() {
        const phoneVal = document.getElementById('auth-phone').value;
        const passVal = document.getElementById('auth-pass').value;

        if (phoneVal.length < 10 || passVal.length < 4) {
            return alert("Please enter valid mobile and password!");
        }

        const payload = { 
            phone: phoneVal, 
            password: passVal 
        };
        if(!isLoginMode) {
            const nameVal = document.getElementById('auth-name').value;
            if (nameVal.length < 2) return alert("Please enter your name!");
            payload.name = nameVal;
        }
 
        try {
            const res = await fetch('auth.php', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload) 
            });
            const d = await res.json();
            if(d.status === 'success') {
                localStorage.setItem('quickmart_user', JSON.stringify(d.user));
                location.reload();
            } else { 
                alert(d.message); 
            }
        } catch(e) {
            alert("Connection error! Please check network.");
        }
    }
 
    function initDashboard() {
        document.getElementById('profile-content').style.display = 'block';
        document.getElementById('u-display-name').innerText = user.name;
        document.getElementById('u-display-phone').innerText = "+91 " + user.phone;
        document.getElementById('u-initials').innerText = user.name.charAt(0).toUpperCase();
        
        syncWallet(); 
        fetchDatabaseAddress(); 
        loadOrders();
    }
 
    async function syncWallet() {
        if(!user) return;
        try {
            const res = await fetch('get_wallet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: user.phone })
            });
            const d = await res.json();
            if(d.status === 'success') {
                document.getElementById('u-wallet-amt').innerText = parseFloat(d.wallet).toFixed(2);
                user.wallet = d.wallet;
                localStorage.setItem('quickmart_user', JSON.stringify(user));
            }
        } catch(e) { console.log("Wallet sync error"); }
    }
 
    async function fetchDatabaseAddress() {
        if(!user) return;
        try {
            const res = await fetch('get_address.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: user.phone })
            });
            const d = await res.json();
            if(d.status === 'success' && d.address) {
                const a = d.address;
                document.getElementById('addr-name').value = a.receiver_name || '';
                document.getElementById('addr-email').value = a.email || '';
                document.getElementById('addr-pin').value = a.pincode || '';
                document.getElementById('addr-city').value = a.city || '';
                document.getElementById('addr-house').value = a.house_no || '';
                document.getElementById('addr-full').value = a.full_address || '';
                document.getElementById('addr-mark').value = a.landmark || '';
                document.getElementById('addr-lat').value = a.lat || '';
                document.getElementById('addr-lng').value = a.lng || '';
                if(a.lat) document.getElementById('gps-btn').innerText = "Location Coordinates Captured ✓";
            }
        } catch(e) { console.log("Address fetch failed"); }
    }
 
    function switchTab(id, el) {
        document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
        document.querySelectorAll('.tab-link').forEach(t => t.classList.remove('active'));
        document.getElementById(id).style.display = 'block';
        el.classList.add('active');
    }
 
    async function loadOrders() {
        if(!user) return;
        const list = document.getElementById('orders-list');
        list.innerHTML = "<p style='text-align:center; padding:20px; color:#94a3b8; font-weight:600;'>Loading orders...</p>";

        try {
            const res = await fetch('get_orders.php', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: user.phone }) 
            });
            const d = await res.json();
            
            if(d.orders && d.orders.length) {
                list.innerHTML = d.orders.map(o => {
                    const isDelivered = o.order_status.toLowerCase() === 'delivered';
                    const isOut = o.order_status.toLowerCase() === 'out' || o.order_status.toLowerCase() === 'out for delivery';
                    
                    let pinBlock = '';
                    if(!isDelivered) {
                        pinBlock = `
                        <div style="margin-top:12px; background:#fffbeb; border:1.5px dashed #f59e0b; padding:10px 15px; border-radius:10px; display:inline-block; font-weight:600;">
                            <span style="font-size:11px; color:#d97706;">Secret Delivery PIN (Give to Rider):</span> <br>
                            <b style="font-size:18px; color:#b45309; letter-spacing:4px;">${o.delivery_pin ? o.delivery_pin : 'Wait'}</b>
                        </div>`;
                    }

                    let statusColor = 'var(--secondary)';
                    if(o.order_status === 'Pending') statusColor = '#f59e0b';
                    if(o.order_status === 'Confirmed') statusColor = 'var(--primary)';
                    if(isOut) statusColor = '#3b82f6';
                    if(isDelivered) statusColor = '#10b981';

                    return `
                    <div class="order-card-wrap">
                        <span style="float:right; font-size:10px; font-weight:800; color:white; background:${statusColor}; padding:4px 8px; border-radius:6px; text-transform:uppercase;">${o.order_status}</span>
                        <b style="font-size:14px; font-weight:800; color:var(--primary);">#SEVZO-${o.id}</b><br>
                        <small style="color:#94a3b8; font-weight:600;">${o.time_formatted || ''}</small>
                        <div style="margin-top:10px; font-weight:900; font-size: 16px;">Total Paid: ₹${o.total_amount}</div>
                        
                        ${pinBlock}
                        
                        <div style="margin-top: 14px;">
                            <button onclick='trackOrder(${JSON.stringify(o).replace(/'/g, "\\'")})' style="border:none; background:var(--primary); color:white; padding:11px 20px; border-radius:10px; font-size:12px; font-weight:800; cursor:pointer; width:100%; box-shadow:0 4px 10px rgba(59,0,104,0.1);">
                                <i class="fas fa-location-arrow"></i> Visual Timeline & Live Map
                            </button>
                        </div>
                    </div>`;
                }).join('');
            } else {
                list.innerHTML = "<p style='text-align:center; padding:35px 20px; color:#94a3b8; font-weight:600;'>No orders found. Shop from SEVZO Mall now!</p>";
            }
        } catch(e) {
            list.innerHTML = "<p style='text-align:center; padding:20px; color:red;'>Connection issue loading orders.</p>";
        }
    }
 
    function trackOrder(o) {
        document.getElementById('tracking-view').style.display = 'flex';
        document.getElementById('track-id').innerText = o.id;
        document.getElementById('track-time').innerText = o.time_formatted;
        
        const status = o.order_status.toLowerCase();
        
        // Reset steps
        document.querySelectorAll('.step').forEach(s => s.className = 'step');
        
        document.getElementById('step-placed').className = 'step completed';
        
        if (status === 'confirmed') {
            document.getElementById('step-confirmed').className = 'step active';
            document.getElementById('map-container').style.display = 'none';
        } else if (status === 'out' || status === 'out for delivery') {
            document.getElementById('step-confirmed').className = 'step completed';
            document.getElementById('step-out').className = 'step active';
            
            // Show Live Map of customer location (simulated tracking partner destination)
            document.getElementById('map-container').style.display = 'block';
            let lat = o.lat ? o.lat : '26.8924';
            let lng = o.lng ? o.lng : '75.8073';
            document.getElementById('map-frame').src = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;
        } else if (status === 'delivered') {
            document.getElementById('step-confirmed').className = 'step completed';
            document.getElementById('step-out').className = 'step completed';
            document.getElementById('step-delivered').className = 'step completed';
            document.getElementById('map-container').style.display = 'none';
        } else {
            // Pending
            document.getElementById('step-placed').className = 'step active';
            document.getElementById('map-container').style.display = 'none';
        }
    }
 
    function closeTracking() { document.getElementById('tracking-view').style.display = 'none'; }
    
    function logout() { 
        localStorage.clear(); 
        location.reload(); 
    }
 
    function fetchGPS() {
        const btn = document.getElementById('gps-btn');
        btn.innerText = "Accessing Geolocation...";
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(p => {
                document.getElementById('addr-lat').value = p.coords.latitude;
                document.getElementById('addr-lng').value = p.coords.longitude;
                btn.innerText = "Location Captured ✓ (" + p.coords.latitude.toFixed(4) + ", " + p.coords.longitude.toFixed(4) + ")";
                btn.style.background = "#e8f5e9";
                btn.style.borderColor = "#a5d6a7";
                btn.style.color = "#2e7d32";
            }, err => {
                alert("GPS permission denied! Using manual address.");
                btn.innerText = "GPS Permission Failed - Enter Address manually";
                btn.style.background = "#ffebee";
                btn.style.borderColor = "#ef9a9a";
                btn.style.color = "#c62828";
            });
        } else {
            alert("GPS not supported by your browser.");
        }
    }
 
    async function saveCompleteAddress() {
        const nameVal = document.getElementById('addr-name').value;
        const pinVal = document.getElementById('addr-pin').value;
        const cityVal = document.getElementById('addr-city').value;
        const houseVal = document.getElementById('addr-house').value;
        const fullVal = document.getElementById('addr-full').value;

        if(!nameVal || !pinVal || !cityVal || !houseVal || !fullVal) {
            return alert("Please fill all mandatory fields (Name, Pincode, City, House No, Full Address)!");
        }

        const addr = {
            phone: user.phone,
            receiver_name: nameVal,
            email: document.getElementById('addr-email').value,
            pincode: pinVal,
            city: cityVal,
            house: houseVal,
            full_address: fullVal,
            landmark: document.getElementById('addr-mark').value,
            lat: document.getElementById('addr-lat').value,
            lng: document.getElementById('addr-lng').value
        };
 
        try {
            const res = await fetch('save_address.php', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(addr) 
            });
            const d = await res.json();
            if(d.status === 'success') {
                alert("🎉 Delivery address saved successfully!");
                const tabAddr = document.getElementById('tab-link-address');
                const tabOrd = document.getElementById('tab-link-orders');
                switchTab('tab-orders', tabOrd);
                loadOrders();
            } else {
                alert("Failed to save: " + d.message);
            }
        } catch(e) {
            alert("Network issue! Check connection.");
        }
    }

    function setRecharge(amt) {
        document.getElementById('recharge-val').value = amt;
    }

    async function handleRecharge() {
        const amt = parseFloat(document.getElementById('recharge-val').value);
        if (isNaN(amt) || amt <= 0) return alert("Please enter a valid amount to recharge.");

        try {
            // Simulated payment authorization
            // Recharging wallet: read current balance + new amount, then send POST to admin_api
            const newBal = walletBalance + amt;
            const res = await fetch('admin_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update_wallet',
                    id: user.id,
                    wallet: newBal
                })
            });
            const d = await res.json();
            if (d.status === 'success') {
                alert("💰 Simulated recharge successful! ₹" + amt + " added to your wallet.");
                document.getElementById('recharge-val').value = '';
                syncWallet();
            } else {
                alert("Recharge failed: " + d.message);
            }
        } catch(e) {
            alert("Recharge error, please try again.");
        }
    }
</script>
</body>
</html>
