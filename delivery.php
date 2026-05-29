<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SEVZO Delivery Partner</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #0f172a; 
            --accent: #f59e0b; 
            --bg: #f1f5f9; 
            --green: #10b981; 
            --secondary: #ff005c;
            --white: #ffffff;
            --gray-light: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: var(--bg); color: #334155; padding-bottom: 90px; }
        
        #auth-screen { padding: 50px 25px; text-align: center; display: none; min-height: 100vh; background: white; }
        .logo-wrap { font-size: 32px; font-weight: 900; color: var(--primary); margin-bottom: 10px; }
        .logo-wrap span { color: var(--secondary); }
        .input-box { width: 100%; padding: 15px; margin-bottom: 15px; border-radius: 12px; border: 1.5px solid var(--gray-light); font-size: 15px; outline:none; font-weight: 600; background: #f8fafc; }
        .input-box:focus { border-color: var(--primary); background: white; }
        .btn-main { width: 100%; padding: 16px; background: var(--primary); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(15,23,42,0.15); }
        .btn-main:active { transform: scale(0.97); }

        .header { background: var(--primary); color: white; padding: 25px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom-left-radius: 24px; border-bottom-right-radius: 24px; }
        .header h3 { font-size: 18px; font-weight: 800; }
        .duty-toggle { background: white; color: var(--primary); padding: 8px 18px; border-radius: 20px; font-weight: 800; font-size: 13px; cursor: pointer; border: none; transition: 0.3s; }
        .duty-toggle.online { background: var(--green); color: white; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4); }

        .tabs { display: flex; margin: 15px 20px; background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.02); border: 1px solid var(--gray-light); }
        .tab { flex: 1; text-align: center; padding: 14px; font-weight: 800; color: #94a3b8; cursor: pointer; transition: 0.3s; font-size: 13px; text-transform: uppercase; }
        .tab.active { background: var(--accent); color: white; }

        .order-card { background: white; margin: 15px 20px; padding: 18px; border-radius: 18px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border-left: 5px solid var(--accent); border-top: 1px solid var(--gray-light); border-right: 1px solid var(--gray-light); border-bottom: 1px solid var(--gray-light); }
        .order-card.active-card { border-left-color: var(--green); }
        .btn-action { width: 100%; padding: 12px; margin-top: 12px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.2s; font-size: 13px; }
        .btn-accept { background: var(--primary); color: white; }
        .btn-deliver { background: var(--green); color: white; }

        /* Bottom Nav */
        .bottom-nav { position: fixed; bottom: 0; left: 0; width: 100%; background: white; display: flex; justify-content: space-around; padding: 12px 10px 15px; border-top: 1px solid var(--gray-light); z-index: 9999; box-shadow: 0 -4px 15px rgba(0,0,0,0.03); }
        .nav-item { text-align: center; color: #94a3b8; font-size: 11px; cursor: pointer; flex: 1; transition: 0.3s; font-weight: 700; }
        .nav-item.active { color: var(--accent); }
        .nav-item i { font-size: 22px; margin-bottom: 3px; display: block; }

        .main-view { display: none; }
        .main-view.active { display: block; }
        
        .stat-card { background: white; padding: 20px; margin: 15px; border-radius: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.02); border: 1px solid var(--gray-light); }
        .profile-pic-container { width: 120px; height: 120px; margin: 25px auto; border-radius: 50%; border: 3px solid var(--accent); overflow: hidden; position: relative; background: #f8fafc; display:flex; align-items:center; justify-content:center;}
        .profile-pic-container img { width: 100%; height: 100%; object-fit: cover; }
        .cam-overlay { position: absolute; bottom: 0; width: 100%; background: rgba(15,23,42,0.6); color: white; text-align: center; padding: 6px; cursor: pointer; font-size: 11px; font-weight: bold; }

        #map-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; z-index: 10000; display: none; flex-direction: column; }
    </style>
</head>
<body>

<div id="auth-screen">
    <div class="logo-wrap">SEV<span>ZO</span></div>
    <i class="fas fa-motorcycle" style="font-size: 55px; color: var(--accent); margin: 15px 0 20px;"></i>
    <h2 style="font-weight: 900; color:var(--primary);">Partner Portal</h2>
    <p style="color: #64748b; margin-bottom: 30px; font-size:13px; font-weight:600;">Go online and earn money per delivery</p>
    <input type="number" id="l-phone" class="input-box" placeholder="Phone Number (e.g. 9876543210)">
    <input type="password" id="l-pass" class="input-box" placeholder="Password (e.g. rider123)">
    <button class="btn-main" onclick="riderLogin()">Sign In & Go Online</button>
</div>

<div id="view-home" class="main-view active">
    <div class="header">
        <div>
            <h3 id="rider-name">Rider Name</h3>
            <small id="duty-status" style="font-weight: 600; opacity: 0.9;">Offline</small>
        </div>
        <button id="toggle-btn" class="duty-toggle" onclick="toggleDuty()">Go Online</button>
    </div>
    <div class="tabs">
        <div class="tab active" onclick="switchOrderTab('new')">Available <span id="badge-new" style="background:var(--secondary);color:white;border-radius:50%;padding:2px 6px;font-size:10px;display:none;margin-left:4px;">0</span></div>
        <div class="tab" onclick="switchOrderTab('active')">My Active Delivery</div>
    </div>
    <div id="list-new"></div>
    <div id="list-active" style="display: none;"></div>
</div>

<div id="view-history" class="main-view">
    <div class="header"><h3>Delivery History</h3></div>
    <div style="display: flex; gap: 15px; margin: 15px 20px;">
        <div class="stat-card" style="flex:1; margin:0;">
            <p style="color:#64748b; font-size:12px;">Earnings</p>
            <h2 style="color:var(--green); font-weight: 900; margin-top: 4px;">₹<span id="hist-rev">0.00</span></h2>
        </div>
        <div class="stat-card" style="flex:1; margin:0;">
            <p style="color:#64748b; font-size:12px;">Deliveries</p>
            <h2 style="color:var(--primary); font-weight: 900; margin-top: 4px;"><span id="hist-count">0</span></h2>
        </div>
    </div>
    <h4 style="margin: 15px 20px; font-weight:800; color:var(--primary);">Completed Shipments</h4>
    <div id="list-history"></div>
</div>

<div id="view-profile" class="main-view">
    <div class="header"><h3>Partner Profile</h3></div>
    
    <div class="profile-pic-container">
        <img id="profile-img" src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
        <div class="cam-overlay" onclick="document.getElementById('file-upload').click()"><i class="fas fa-camera"></i> Update</div>
        <input type="file" id="file-upload" accept="image/*" style="display:none" onchange="uploadSelfie(event)">
    </div>

    <div style="padding: 15px 25px;">
        <label style="font-size:11px; color:#64748b; font-weight: 700; text-transform: uppercase;">Mobile Number</label>
        <input type="number" id="prof-phone" class="input-box" placeholder="Mobile Number" style="margin-top:4px;">
        
        <label style="font-size:11px; color:#64748b; font-weight: 700; text-transform: uppercase;">Gmail ID</label>
        <input type="email" id="prof-email" class="input-box" placeholder="Email Address" style="margin-top:4px;">
        
        <button class="btn-main" onclick="saveProfile()" style="margin-bottom:15px; background:var(--accent);">Update Profile Info</button>
        <button class="btn-main" onclick="logout()" style="background:#ef4444;">Logout Account</button>
    </div>
</div>

<div id="bottom-nav" class="bottom-nav" style="display:none;">
    <div class="nav-item active" onclick="navTo('home', this)"><i class="fas fa-motorcycle"></i>Duty</div>
    <div class="nav-item" onclick="navTo('history', this); loadHistory();"><i class="fas fa-history"></i>History</div>
    <div class="nav-item" onclick="navTo('profile', this); loadProfile();"><i class="fas fa-user-alt"></i>Profile</div>
</div>

<!-- Map Details Overlay -->
<div id="map-overlay">
    <div class="header" style="border-radius: 0;">
        <i class="fas fa-arrow-left" style="font-size: 20px; cursor: pointer;" onclick="closeMap()"></i>
        <h3>Order Navigation</h3><div></div>
    </div>
    <iframe id="map-frame" width="100%" height="240" frameborder="0" style="border:0;"></iframe>
    
    <div style="padding: 20px; overflow-y: auto; flex: 1;">
        <h2 style="color: var(--primary); margin-bottom: 10px; font-weight:900;">Collect Cash: ₹<span id="det-amount">0</span></h2>
        <div style="background: #f8fafc; padding: 15px; border-radius: 14px; border: 1.5px solid var(--gray-light); margin-bottom: 15px; font-size:14px; line-height:1.6; font-weight: 600;">
            <b><i class="fas fa-user"></i> Customer: <span id="det-name">Name</span></b><br>
            <i class="fas fa-phone-alt"></i> Phone: +91 <span id="det-phone">Phone</span><br><br>
            <i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> Destination: <span id="det-address">Full Address</span><br>
            <b>Pincode:</b> <span id="det-pin">000000</span>
            <div id="det-items-box" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--gray-light); font-size: 13px;">
                <!-- Filled dynamically -->
            </div>
        </div>
        
        <div id="nav-btn-container"></div>

        <div style="background: #fff; padding: 15px; border-radius: 12px; border: 2px dashed var(--accent); margin-bottom: 15px; text-align:center;">
            <p style="font-size: 13px; color: #64748b; margin-bottom: 10px; font-weight: 700;">Ask customer for 4-digit Security PIN</p>
            <input type="number" id="delivery-pin-input" placeholder="Enter PIN (e.g. 5621)" class="input-box" style="text-align:center; font-size: 20px; letter-spacing: 5px; font-weight:bold; margin-bottom: 0;">
        </div>

        <!-- Delivery Proof Photo Section -->
        <div style="background: #fff; padding: 15px; border-radius: 12px; border: 1.5px solid var(--gray-light); margin-bottom: 15px; text-align:center;">
            <p style="font-size: 13px; color: #64748b; margin-bottom: 8px; font-weight: 700;">Upload Delivery Proof Photo (Required)</p>
            <input type="file" id="delivery-proof-upload" accept="image/*" style="display:none;" onchange="handleDeliveryProof(event)">
            
            <div id="proof-preview-container" onclick="document.getElementById('delivery-proof-upload').click()" style="width:100%; height:120px; border:2px dashed #cbd5e1; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; background:#f8fafc; transition: 0.3s;">
                <i class="fas fa-camera" style="font-size:24px; color:#94a3b8; margin-bottom:6px;"></i>
                <span id="proof-preview-text" style="font-size:12px; color:#64748b; font-weight:600;">Tap to open camera / upload photo</span>
                <img id="proof-preview-img" style="width:100%; height:100%; object-fit:cover; display:none;">
            </div>
        </div>

        <button id="btn-final-deliver" class="btn-main btn-deliver" onclick="markDelivered()" disabled style="opacity:0.5; cursor:not-allowed;">Verify PIN & Deliver Package</button>
    </div>
</div>

<script>
    let rider = JSON.parse(localStorage.getItem('qm_rider'));
    let isOnline = false, pollInterval = null, knownOrderIds = []; 
    let currentLat = null, currentLng = null, watchId = null;
    let globalActiveOrders = [];
    let deliveryProofBase64 = null;

    window.onload = () => {
        if(!rider) { 
            document.getElementById('auth-screen').style.display = 'block'; 
            document.getElementById('view-home').classList.remove('active'); 
            document.getElementById('bottom-nav').style.display = 'none';
        } else { 
            document.getElementById('auth-screen').style.display = 'none'; 
            initApp(); 
        }
    };

    function initApp() {
        document.querySelectorAll('.main-view').forEach(v => v.classList.remove('active'));
        document.getElementById('view-home').classList.add('active');
        document.getElementById('bottom-nav').style.display = 'flex';
        
        document.getElementById('rider-name').innerText = rider.name;
        if(rider.is_online == 1) { 
            isOnline = true; 
            updateDutyUI(); 
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    currentLat = pos.coords.latitude; 
                    currentLng = pos.coords.longitude;
                    startLocationTracking();
                    startPolling();
                }, err => { startPolling(); });
            } else { startPolling(); }
        }
    }

    function navTo(view, el) {
        document.querySelectorAll('.main-view').forEach(v => v.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('view-' + view).classList.add('active'); 
        el.classList.add('active');
    }

    function startLocationTracking() {
        if (navigator.geolocation) {
            watchId = navigator.geolocation.watchPosition(
                (pos) => { 
                    currentLat = pos.coords.latitude; 
                    currentLng = pos.coords.longitude; 
                    // Silently post location to update status
                    fetch('delivery_api.php', { method: 'POST', body: JSON.stringify({ action: 'toggle_status', id: rider.id, status: 1, lat: currentLat, lng: currentLng }) });
                },
                (err) => { console.warn("GPS tracking issue: ", err); },
                { enableHighAccuracy: true }
            );
        }
    }
    
    function stopLocationTracking() {
        if(watchId !== null && navigator.geolocation) { navigator.geolocation.clearWatch(watchId); }
    }

    /* Visibility visibility handler */
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === "hidden" && isOnline) { 
            // Avoid forced offline for demo convenience in background, or keep it optional
            console.log("Rider moved to background");
        }
    });

    /* --- AUTH & DUTY --- */
    async function riderLogin() {
        const phoneVal = document.getElementById('l-phone').value;
        const passVal = document.getElementById('l-pass').value;
        if(!phoneVal || !passVal) return alert("Please enter mobile and password!");

        const res = await fetch('delivery_api.php', { 
            method: 'POST', 
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'login', phone: phoneVal, password: passVal }) 
        });
        const d = await res.json();
        if(d.status === 'success') { 
            localStorage.setItem('qm_rider', JSON.stringify(d.user)); 
            location.reload(); 
        } else { 
            alert(d.message); 
        }
    }
    
    function logout() { 
        localStorage.removeItem('qm_rider'); 
        location.reload(); 
    }

    async function toggleDuty() {
        if(!isOnline) {
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (pos) => {
                    currentLat = pos.coords.latitude;
                    currentLng = pos.coords.longitude;
                    isOnline = true; 
                    rider.is_online = 1; 
                    localStorage.setItem('qm_rider', JSON.stringify(rider));
                    
                    await fetch('delivery_api.php', { 
                        method: 'POST', 
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ action: 'toggle_status', id: rider.id, status: 1, lat: currentLat, lng: currentLng }) 
                    });
                    
                    startLocationTracking(); 
                    updateDutyUI(); 
                    startPolling();
                }, (err) => { 
                    alert("Please allow GPS location access to start SEVZO duty!"); 
                });
            } else { alert("Geolocation not supported by browser."); }
        } else {
            isOnline = false; 
            rider.is_online = 0; 
            localStorage.setItem('qm_rider', JSON.stringify(rider));
            
            await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'toggle_status', id: rider.id, status: 0 }) 
            });
            
            stopLocationTracking(); 
            updateDutyUI(); 
            clearInterval(pollInterval); 
            clearLists();
        }
    }

    function updateDutyUI() {
        const btn = document.getElementById('toggle-btn'); 
        const st = document.getElementById('duty-status');
        if(isOnline) { 
            btn.innerText = "Go Offline"; 
            btn.className = "duty-toggle online"; 
            st.innerText = "Online - Looking for orders"; 
        } else { 
            btn.innerText = "Go Online"; 
            btn.className = "duty-toggle"; 
            st.innerText = "Offline"; 
        }
    }

    /* --- ORDERS & POLLING --- */
    function startPolling() { 
        fetchOrders(); 
        pollInterval = setInterval(fetchOrders, 6000); 
    }
    
    async function fetchOrders() {
        const payload = { action: 'get_orders', partner_id: rider.id };
        if(currentLat && currentLng) { payload.lat = currentLat; payload.lng = currentLng; }

        try {
            const res = await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(payload) 
            });
            const d = await res.json();
            if(d.status === 'success') {
                renderNewOrders(d.new_orders); 
                renderActiveOrders(d.active_orders);
                d.new_orders.forEach(order => {
                    if(!knownOrderIds.includes(order.id)) { 
                        knownOrderIds.push(order.id); 
                        triggerAlert(); 
                    }
                });
            }
        } catch(e) {}
    }

    function renderNewOrders(orders) {
        const list = document.getElementById('list-new'), badge = document.getElementById('badge-new');
        if(orders.length > 0) {
            badge.style.display = 'inline-block'; 
            badge.innerText = orders.length;
            list.innerHTML = orders.map(o => `
                <div class="order-card">
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;"><b style="color:var(--primary);">#SEVZO-${o.id}</b><b style="color:var(--green);">₹${o.total_amount}</b></div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 10px; font-size: 13px; color: #475569; margin-bottom: 10px; border: 1px solid var(--gray-light);">
                        <b><i class="fas fa-map-marker-alt"></i> Delivery Address:</b> ${o.house_no ? o.house_no : ''}, ${o.full_address || o.delivery_address || o.city}<br>
                        <div style="margin-top:5px; padding-top:5px; border-top:1px solid #e2e8f0; font-weight:700; color:var(--primary);">
                            <i class="fas fa-wallet"></i> Earnings on Delivery: ₹25.00
                        </div>
                    </div>
                    <button class="btn-action btn-accept" onclick="acceptOrder(${o.id})">Accept Delivery Shipment</button>
                </div>
            `).join('');
        } else { 
            badge.style.display = 'none'; 
            list.innerHTML = "<p style='text-align:center; padding:40px 20px; color:#94a3b8; font-weight:600;'>No new orders nearby. Keep checking...</p>"; 
        }
    }

    function renderActiveOrders(orders) {
        globalActiveOrders = orders;
        const list = document.getElementById('list-active');
        if(orders.length > 0) {
            list.innerHTML = orders.map(o => {
                const itemsHtml = o.items ? `
                    <div style="margin: 8px 0 12px; font-size:12px; color:#475569; background:#f8fafc; padding:10px; border-radius:8px; border:1px solid #cbd5e1; text-align:left;">
                        <span style="font-weight:700; display:block; margin-bottom:4px; color:var(--primary);"><i class="fas fa-shopping-basket"></i> Package Items:</span>
                        ${o.items.map(item => `<div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                            <span>${item.product_name}</span>
                            <b>x${item.quantity}</b>
                        </div>`).join('')}
                    </div>
                ` : '';

                return `
                    <div class="order-card active-card">
                        <b style="color:var(--green);"><i class="fas fa-spinner fa-spin"></i> Active shipment: #SEVZO-${o.id}</b>
                        <h3 style="margin: 8px 0; font-size: 18px; font-weight:800;">Collect ₹${o.total_amount}</h3>
                        ${itemsHtml}
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 12px; font-weight:600;"><i class="fas fa-user"></i> Customer: ${o.receiver_name ? o.receiver_name : o.customer_name}</p>
                        <button class="btn-action btn-deliver" onclick='openMap(${o.id})'>
                            <i class="fas fa-location-arrow"></i> Navigate & Verify PIN
                        </button>
                    </div>
                `;
            }).join('');
        } else { 
            list.innerHTML = "<p style='text-align:center; padding:40px 20px; color:#94a3b8; font-weight:600;'>No active shipments. Accept a new order!</p>"; 
        }
    }

    async function acceptOrder(orderId) {
        const payload = { action: 'accept_order', order_id: orderId, partner_id: rider.id };
        if(currentLat && currentLng) { payload.lat = currentLat; payload.lng = currentLng; }
        
        try {
            const res = await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(payload) 
            });
            const d = await res.json();
            if (d.status === 'success') {
                fetchOrders(); 
                switchOrderTab('active');
            } else {
                alert(d.message);
            }
        } catch(e) {
            alert("Error accepting order.");
        }
    }

    /* --- MAP & DELIVERY --- */
    let currentTrackOrder = null;
    function openMap(orderId) {
        const order = globalActiveOrders.find(x => x.id == orderId);
        currentTrackOrder = order; 
        document.getElementById('map-overlay').style.display = 'flex'; 
        document.getElementById('delivery-pin-input').value = '';
        
        // Reset delivery proof
        deliveryProofBase64 = null;
        document.getElementById('delivery-proof-upload').value = '';
        const previewImg = document.getElementById('proof-preview-img');
        const previewText = document.getElementById('proof-preview-text');
        const previewIcon = document.querySelector('#proof-preview-container i');
        if (previewImg) {
            previewImg.src = '';
            previewImg.style.display = 'none';
        }
        if (previewText) previewText.style.display = 'block';
        if (previewIcon) previewIcon.style.display = 'block';
        
        const btn = document.getElementById('btn-final-deliver');
        if (btn) {
            btn.setAttribute('disabled', 'true');
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
        }

        document.getElementById('det-amount').innerText = order.total_amount; 
        document.getElementById('det-name').innerText = order.receiver_name ? order.receiver_name : order.customer_name;
        document.getElementById('det-phone').innerText = order.customer_phone; 
        document.getElementById('det-address').innerText = `${order.house_no || ''}, ${order.full_address || order.delivery_address}`;
        document.getElementById('det-pin').innerText = order.pincode ? order.pincode : 'N/A';
        
        // Dynamic items display
        if (order.items && order.items.length > 0) {
            document.getElementById('det-items-box').innerHTML = `
                <b style="color:var(--primary);"><i class="fas fa-shopping-basket"></i> Items in Package:</b>
                <ul style="padding-left:15px; margin-top:4px; text-align:left;">
                    ${order.items.map(item => `<li>${item.product_name} (x${item.quantity})</li>`).join('')}
                </ul>
            `;
            document.getElementById('det-items-box').style.display = 'block';
        } else {
            document.getElementById('det-items-box').style.display = 'none';
        }

        let destLat = order.lat ? order.lat : 26.8924; 
        let destLng = order.lng ? order.lng : 75.8073; 

        if(currentLat && currentLng) {
            document.getElementById('map-frame').src = `https://maps.google.com/maps?saddr=${currentLat},${currentLng}&daddr=${destLat},${destLng}&output=embed`;
            document.getElementById('nav-btn-container').innerHTML = `
                <a href="https://www.google.com/maps/dir/?api=1&origin=${currentLat},${currentLng}&destination=${destLat},${destLng}" target="_blank" class="btn-main" style="display:block; text-align:center; text-decoration:none; margin-bottom:15px; background:#4285f4; color:white;">
                    <i class="fas fa-directions"></i> Start Live Navigation in Google Maps
                </a>`;
        } else {
            document.getElementById('map-frame').src = `https://maps.google.com/maps?q=${destLat},${destLng}&z=16&output=embed`;
            document.getElementById('nav-btn-container').innerHTML = `
                <a href="https://www.google.com/maps/search/?api=1&query=${destLat},${destLng}" target="_blank" class="btn-main" style="display:block; text-align:center; text-decoration:none; margin-bottom:15px; background:#4285f4; color:white;">
                    <i class="fas fa-map-marker-alt"></i> Navigate via Google Maps
                </a>`;
        }
    }

    function closeMap() { document.getElementById('map-overlay').style.display = 'none'; }
    
    function handleDeliveryProof(event) {
        const file = event.target.files[0];
        if(!file) return;
        const reader = new FileReader();
        reader.onloadend = () => {
            deliveryProofBase64 = reader.result;
            
            // Update preview UI
            const previewImg = document.getElementById('proof-preview-img');
            const previewText = document.getElementById('proof-preview-text');
            const previewIcon = document.querySelector('#proof-preview-container i');
            
            if (previewImg) {
                previewImg.src = deliveryProofBase64;
                previewImg.style.display = 'block';
            }
            if (previewText) previewText.style.display = 'none';
            if (previewIcon) previewIcon.style.display = 'none';
            
            // Enable button
            const btn = document.getElementById('btn-final-deliver');
            if (btn) {
                btn.removeAttribute('disabled');
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
        };
        reader.readAsDataURL(file);
    }

    async function markDelivered() {
        const pinValue = document.getElementById('delivery-pin-input').value;
        if(!pinValue) return alert("Please enter the customer PIN.");
        if(!deliveryProofBase64) return alert("Please upload/capture a delivery proof photo first!");
        
        try {
            const res = await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ 
                    action: 'complete_order', 
                    order_id: currentTrackOrder.id, 
                    pin: pinValue,
                    delivery_proof: deliveryProofBase64 
                }) 
            });
            const d = await res.json();
            if(d.status === 'success') { 
                closeMap(); 
                fetchOrders(); 
                alert("✅ Order Delivered Successfully! ₹25.00 added to your history."); 
            } else { 
                alert(d.message); 
            }
        } catch(e) {
            alert("Connection error marking order delivered.");
        }
    }

    /* --- HISTORY --- */
    async function loadHistory() {
        document.getElementById('list-history').innerHTML = "<p style='text-align:center; padding:20px;'>Syncing history...</p>";
        try {
            const res = await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'get_history', partner_id: rider.id }) 
            });
            const d = await res.json();
            if(d.status === 'success') {
                document.getElementById('hist-rev').innerText = parseFloat(d.total_revenue).toFixed(2);
                document.getElementById('hist-count').innerText = d.total_deliveries;
                document.getElementById('list-history').innerHTML = d.history.length ? d.history.map(h => `
                    <div class="order-card" style="border-left:5px solid var(--primary)">
                        <b style="color:#0f172a;">Order #SEVZO-${h.id}</b> <span style="float:right; color:var(--green); font-weight:800;">+ ₹25.00</span><br>
                        <small style="color:#94a3b8; font-weight:600;">Time: ${h.created_at} | Method: ${h.payment_method}</small>
                    </div>
                `).join('') : "<p style='text-align:center; margin-top:20px; color:#94a3b8; font-weight:600;'>No history recorded yet.</p>";
            }
        } catch(e) {}
    }

    /* --- PROFILE --- */
    function loadProfile() {
        document.getElementById('prof-phone').value = rider.phone || '';
        document.getElementById('prof-email').value = rider.email || '';
        if(rider.profile_pic) document.getElementById('profile-img').src = rider.profile_pic;
    }

    async function saveProfile() {
        const phoneVal = document.getElementById('prof-phone').value;
        const emailVal = document.getElementById('prof-email').value;
        if(!phoneVal) return alert("Phone number is required!");

        try {
            const res = await fetch('delivery_api.php', { 
                method: 'POST', 
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'update_profile', partner_id: rider.id, phone: phoneVal, email: emailVal }) 
            });
            const d = await res.json();
            if(d.status === 'success') { 
                localStorage.setItem('qm_rider', JSON.stringify(d.user)); 
                rider = d.user; 
                alert("Profile Details Updated!"); 
            }
        } catch(e) {}
    }

    function uploadSelfie(event) {
        const file = event.target.files[0];
        if(!file) return;
        const reader = new FileReader();
        reader.onloadend = async () => {
            const base64String = reader.result;
            document.getElementById('profile-img').src = base64String;
            try {
                const res = await fetch('delivery_api.php', { 
                    method: 'POST', 
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ action: 'upload_selfie', partner_id: rider.id, image: base64String }) 
                });
                const d = await res.json();
                if(d.status === 'success') { 
                    localStorage.setItem('qm_rider', JSON.stringify(d.user)); 
                    rider = d.user; 
                    alert("Selfie Photo Uploaded!"); 
                }
            } catch(e) { alert("Photo upload failed."); }
        };
        reader.readAsDataURL(file);
    }

    function triggerAlert() {
        if(navigator.vibrate) navigator.vibrate([400, 200, 400]);
        new Audio('https://www.soundjay.com/buttons/sounds/button-1.mp3').play().catch(e=>{});
    }

    function switchOrderTab(tab) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        if(tab === 'new') { 
            document.querySelectorAll('.tab')[0].classList.add('active'); 
            document.getElementById('list-new').style.display = 'block'; 
            document.getElementById('list-active').style.display = 'none'; 
        } else { 
            document.querySelectorAll('.tab')[1].classList.add('active'); 
            document.getElementById('list-new').style.display = 'none'; 
            document.getElementById('list-active').style.display = 'block'; 
        }
    }

    function clearLists() { 
        document.getElementById('list-new').innerHTML = "<p style='text-align:center; padding:30px; color:#94a3b8; font-weight:600;'>Offline.</p>"; 
        document.getElementById('list-active').innerHTML = "<p style='text-align:center; padding:30px; color:#94a3b8; font-weight:600;'>Offline.</p>"; 
        document.getElementById('badge-new').style.display = 'none'; 
    }
</script>
</body>
</html>
