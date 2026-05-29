<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SEVZO Master Admin Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #0f172a; 
            --accent: #f59e0b; 
            --bg: #f8fafc; 
            --green: #10b981; 
            --red: #ef4444; 
            --secondary: #ff005c;
            --white: #ffffff;
            --gray-light: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background: var(--bg); display: flex; height: 100vh; overflow: hidden; color: var(--primary); }

        /* Login Screen */
        #login-screen {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 5000;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
        }
        .login-card h2 {
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .login-card h2 span {
            color: var(--secondary);
        }
        .login-card input {
            width: 100%;
            padding: 14px;
            margin-top: 15px;
            border: 1.5px solid var(--gray-light);
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            font-weight: 600;
            background: #f8fafc;
            transition: 0.3s;
        }
        .login-card input:focus {
            border-color: #1e293b;
            background: white;
        }
        .login-btn {
            width: 100%;
            padding: 15px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }
        .login-btn:active {
            transform: scale(0.97);
        }

        /* Sidebar Responsive */
        .sidebar { width: 260px; background: var(--primary); color: white; display: flex; flex-direction: column; transition: 0.3s; z-index: 1000; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .logo { padding: 25px; font-size: 24px; font-weight: 900; color: var(--secondary); border-bottom: 1px solid #1e293b; text-align: center; letter-spacing: -1px; }
        .logo span { color: white; }
        .nav-link { padding: 16px 20px; cursor: pointer; color: #94a3b8; border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .nav-link:hover { background: #1e293b; color: white; }
        .nav-link.active { background: #1e293b; color: white; border-left: 4px solid var(--secondary); }

        .main { flex: 1; overflow-y: auto; display: flex; flex-direction: column; }
        .top-bar { background: white; padding: 15px 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border-bottom: 1px solid var(--gray-light); }
        .menu-btn { display: none; font-size: 22px; cursor: pointer; }
        .logout-link { font-size: 13px; font-weight: 700; color: var(--red); cursor: pointer; border: 1.5px solid var(--red); padding: 6px 12px; border-radius: 8px; transition: 0.2s; }
        .logout-link:hover { background: var(--red); color: white; }

        .content { padding: 25px; }
        .section-view { display: none; }
        .section-view.active { display: block; }

        /* Stats & Tables */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: white; padding: 25px; border-radius: 16px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1.5px solid var(--gray-light); }
        .stat-card h3 { font-size: 26px; font-weight: 900; color: var(--primary); margin-top: 5px; }
        .stat-card p { font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .table-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1.5px solid var(--gray-light); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { background: #f8fafc; color: #475569; font-weight: 800; padding: 14px 12px; font-size: 13px; text-align: left; border-bottom: 1.5px solid var(--gray-light); }
        td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 600; color: #334155; }
        
        /* Buttons */
        .btn { padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 800; font-size: 12px; transition: 0.2s; }
        .btn-add { background: var(--green); color: white; margin-bottom: 18px; box-shadow: 0 4px 10px rgba(16,185,129,0.2); }
        .btn-edit { background: var(--accent); color: white; margin-right: 6px; }
        .btn-del { background: var(--red); color: white; }
        .btn:active { transform: scale(0.95); }

        /* Modal */
        .modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.4); backdrop-filter: blur(4px); display:none; align-items:center; justify-content:center; z-index:2000; padding:20px; }
        .modal { background:white; padding:30px; border-radius:20px; width:100%; max-width:500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid var(--gray-light); }
        .modal label { font-size: 11px; font-weight: 700; color: #64748b; margin-top: 10px; display: block; text-transform: uppercase; }
        .input-box { width:100%; padding:12px; margin-top: 4px; margin-bottom:12px; border:1.5px solid var(--gray-light); border-radius:10px; outline: none; font-size:14px; font-weight:600; background: #f8fafc; }
        .input-box:focus { border-color: var(--secondary); background: white; }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -260px; height: 100%; }
            .sidebar.open { left: 0; }
            .sidebar-overlay.open { display: block; }
            .menu-btn { display: block; }
        }
    </style>
</head>
<body>

<!-- Admin Login Section -->
<div id="login-screen">
    <div class="login-card">
        <h2>SEV<span>ZO</span> Admin</h2>
        <p style="color:#64748b; font-size:13px; font-weight:600; margin-top:5px;">Please login to manage operations</p>
        <input type="text" id="adm-user" placeholder="Username">
        <input type="password" id="adm-pass" placeholder="Password">
        <button class="login-btn" onclick="handleAdminLogin()">Log In</button>
    </div>
</div>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
<div class="sidebar" id="sidebar">
    <div class="logo">SEV<span>ZO</span> <span>Admin</span></div>
    <div class="nav-link active" onclick="switchTab('dashboard')"><i class="fas fa-home"></i> Dashboard</div>
    <div class="nav-link" onclick="switchTab('orders')"><i class="fas fa-shopping-basket"></i> Orders</div>
    <div class="nav-link" onclick="switchTab('products')"><i class="fas fa-box"></i> Inventory</div>
    <div class="nav-link" onclick="switchTab('users')"><i class="fas fa-users"></i> Customers</div>
    <div class="nav-link" onclick="switchTab('riders')"><i class="fas fa-motorcycle"></i> Riders</div>
</div>

<div class="main">
    <div class="top-bar">
        <div style="display:flex; align-items:center; gap:15px;">
            <i class="fas fa-bars menu-btn" onclick="toggleSidebar()"></i>
            <h2 id="view-title" style="font-weight:900;">Dashboard</h2>
        </div>
        <div class="logout-link" onclick="handleAdminLogout()">Log out</div>
    </div>

    <div class="content">
        <!-- Dashboard Section -->
        <div id="view-dashboard" class="section-view active">
            <div class="stat-grid">
                <div class="stat-card"><p>Delivered Revenue</p><h3>₹<span id="st-rev">0.00</span></h3></div>
                <div class="stat-card"><p>Total Orders</p><h3><span id="st-ord">0</span></h3></div>
                <div class="stat-card"><p>Customers</p><h3><span id="st-usr">0</span></h3></div>
                <div class="stat-card"><p>Active Riders</p><h3><span id="st-rid">0</span></h3></div>
            </div>
        </div>

        <!-- Dynamic Tables View -->
        <div id="view-table" class="section-view">
            <div id="action-btn-container"></div>
            <div class="table-card">
                <table id="main-table"></table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialog -->
<div id="modal-overlay" class="modal-overlay">
    <div class="modal">
        <h3 id="modal-title" style="margin-bottom:15px; font-weight:900; color:var(--primary);">Edit Details</h3>
        <div id="modal-body"></div>
        <div id="modal-footer" style="margin-top:20px; display:flex; gap:10px;">
            <button class="btn btn-add" style="flex:1; margin-bottom:0;" onclick="saveData()">Save Details</button>
            <button class="btn" style="flex:1; background:#cbd5e1; color:#475569;" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

<script>
    let currentTab = 'dashboard';
    let editId = 0;
    let globalOrders = [];
    let globalRiders = [];

    // Session Check
    if (localStorage.getItem('sevzo_admin_logged') === 'true') {
        document.getElementById('login-screen').style.display = 'none';
    }

    function handleAdminLogin() {
        const u = document.getElementById('adm-user').value;
        const p = document.getElementById('adm-pass').value;
        if (u === 'admin' && p === 'admin123') {
            localStorage.setItem('sevzo_admin_logged', 'true');
            document.getElementById('login-screen').style.display = 'none';
            loadDashboard();
        } else {
            alert("Invalid admin credentials!");
        }
    }

    function handleAdminLogout() {
        localStorage.removeItem('sevzo_admin_logged');
        location.reload();
    }

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('open');
    }

    function switchTab(tab) {
        currentTab = tab;
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        
        // Find matching menu element
        const links = document.querySelectorAll('.nav-link');
        links.forEach(l => {
            if(l.innerText.toLowerCase().includes(tab.toLowerCase())) l.classList.add('active');
        });

        document.getElementById('view-title').innerText = tab.charAt(0).toUpperCase() + tab.slice(1);
        document.querySelectorAll('.section-view').forEach(v => v.classList.remove('active'));
        
        if(tab === 'dashboard') {
            document.getElementById('view-dashboard').classList.add('active');
            loadDashboard();
        } else {
            document.getElementById('view-table').classList.add('active');
            fetchData(tab);
        }
        if(window.innerWidth <= 768) toggleSidebar();
    }

    async function req(action, data = {}) {
        data.action = action;
        const res = await fetch('admin_api.php', { 
            method: 'POST', 
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data) 
        });
        return await res.json();
    }

    async function loadDashboard() {
        const d = await req('get_dashboard');
        if (d.status === 'success') {
            document.getElementById('st-rev').innerText = parseFloat(d.stats.revenue).toFixed(2);
            document.getElementById('st-ord').innerText = d.stats.orders;
            document.getElementById('st-usr').innerText = d.stats.users;
            document.getElementById('st-rid').innerText = d.stats.riders;
        }
    }

    async function fetchData(tab) {
        const d = await req('get_' + tab);
        const table = document.getElementById('main-table');
        const btnBox = document.getElementById('action-btn-container');
        btnBox.innerHTML = '';

        if(tab === 'products') {
            btnBox.innerHTML = `<button class="btn btn-add" onclick="openProductModal(null)">+ Add New Product</button>`;
            table.innerHTML = `<tr><th>Img</th><th>Name</th><th>Category</th><th>Price</th><th>Brand</th><th>Action</th></tr>` + 
            d.products.map(p => `<tr>
                <td><img src="${p.image_url}" width="35" height="35" style="object-fit:contain; border-radius:6px;"></td>
                <td><b>${p.name}</b></td>
                <td><span style="background:#e0f2fe; color:#0369a1; padding:3px 8px; border-radius:5px; font-size:11px;">${p.category}</span></td>
                <td>₹${p.price}</td>
                <td>${p.brand ? p.brand : 'SEVZO'}</td>
                <td>
                    <button class="btn btn-edit" onclick='openProductModal(${JSON.stringify(p).replace(/'/g, "&#39;")})'>Edit</button>
                    <button class="btn btn-del" onclick="deleteItem('product', ${p.id})">Del</button>
                </td>
            </tr>`).join('');
        }
        if(tab === 'orders') {
            globalOrders = d.orders;
            // Fetch riders to populate assignment dropdowns
            const rData = await req('get_riders');
            globalRiders = rData.riders;

            table.innerHTML = `<tr><th>ID</th><th>Customer</th><th>Amount</th><th>Status Update</th><th>Assign Rider</th><th>Pin Code</th><th>Action</th></tr>` + 
            d.orders.map(o => {
                const riderOptions = `<option value="">Select Rider</option>` + 
                    globalRiders.map(r => `<option value="${r.id}" ${o.delivery_partner_id==r.id?'selected':''}>${r.name} (${r.is_online==1?'Online':'Offline'})</option>`).join('');

                return `<tr>
                    <td><b>#SEVZO-${o.id}</b></td>
                    <td>${o.customer_name}<br><small style="color:#64748b;">+91 ${o.customer_phone}</small></td>
                    <td><b>₹${o.total_amount}</b></td>
                    <td>
                        <select onchange="updateStatus(${o.id}, this.value)" style="font-size:12px; padding:6px; border-radius:6px; border:1px solid #cbd5e1; outline:none; font-weight:600;">
                            <option value="Pending" ${o.order_status=='Pending'?'selected':''}>Pending</option>
                            <option value="Confirmed" ${o.order_status=='Confirmed'?'selected':''}>Confirmed</option>
                            <option value="Out for Delivery" ${o.order_status=='Out for Delivery'?'selected':''}>Out for Delivery</option>
                            <option value="Delivered" ${o.order_status=='Delivered'?'selected':''}>Delivered</option>
                        </select>
                    </td>
                    <td>
                        <select onchange="assignRider(${o.id}, this.value, '${o.order_status}')" style="font-size:12px; padding:6px; border-radius:6px; border:1px solid #cbd5e1; outline:none; font-weight:600;">
                            ${riderOptions}
                        </select>
                    </td>
                    <td>${o.pincode ? o.pincode : 'N/A'}</td>
                    <td><button class="btn btn-edit" onclick="viewOrderDetails(${o.id})">Details</button></td>
                </tr>`
            }).join('');
        }
        if(tab === 'users') {
            table.innerHTML = `<tr><th>Name</th><th>Phone</th><th>Wallet Balance</th><th>Action</th></tr>` + 
            d.users.map(u => `<tr>
                <td><b>${u.name}</b></td>
                <td>+91 ${u.phone}</td>
                <td><b style="color:var(--green)">₹${parseFloat(u.wallet).toFixed(2)}</b></td>
                <td><button class="btn btn-edit" onclick='openWalletModal(${JSON.stringify(u)})'>Update Wallet</button></td>
            </tr>`).join('');
        }
        if(tab === 'riders') {
            table.innerHTML = `<tr><th>Rider ID</th><th>Name</th><th>Phone</th><th>Status</th><th>Coordinates</th></tr>` + 
            d.riders.map(r => `<tr>
                <td>#RIDER-${r.id}</td>
                <td><b>${r.name}</b></td>
                <td>+91 ${r.phone}</td>
                <td><span style="background:${r.is_online==1?'#d1fae5':'#fee2e2'}; color:${r.is_online==1?'#065f46':'#991b1b'}; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:800; text-transform:uppercase;">${r.is_online==1?'Online':'Offline'}</span></td>
                <td>${r.is_online==1 ? 'Live GPS enabled' : 'Not tracked'}</td>
            </tr>`).join('');
        }
    }

    function openProductModal(p) {
        editId = p ? p.id : 0;
        document.getElementById('modal-overlay').style.display = 'flex';
        document.getElementById('modal-title').innerText = p ? "Edit SEVZO Product" : "Add New SEVZO Product";
        document.getElementById('modal-body').innerHTML = `
            <label>Product Name</label><input type="text" id="m-name" class="input-box" value="${p?p.name:''}">
            <label>Price (₹)</label><input type="number" id="m-price" class="input-box" value="${p?p.price:''}">
            <label>Image URL</label><input type="text" id="m-img" class="input-box" value="${p?p.image_url:''}">
            <label>Category</label>
            <select id="m-cat" class="input-box">
                <option value="Fruits and Vegetables" ${p && p.category=='Fruits and Vegetables'?'selected':''}>Fruits & Veggies</option>
                <option value="Grocery & Kitchen" ${p && p.category=='Grocery & Kitchen'?'selected':''}>Grocery & Kitchen</option>
                <option value="Household Essentials" ${p && p.category=='Household Essentials'?'selected':''}>Dairy & Household</option>
                <option value="Snacks & Drinks" ${p && p.category=='Snacks & Drinks'?'selected':''}>Snacks & Drinks</option>
            </select>
            <label>Available Pincodes (Comma separated, or ALL)</label>
            <input type="text" id="m-pin" class="input-box" value="${p?p.available_pincodes:'ALL'}">
            <label>Brand Name</label><input type="text" id="m-brand" class="input-box" value="${p && p.brand ? p.brand : 'SEVZO Fresh'}">
            <label>Product Description</label><textarea id="m-desc" class="input-box" style="height:80px; resize:none;">${p && p.description ? p.description : ''}</textarea>
            <label>Highlights (Separated by semicolon ';')</label><input type="text" id="m-highlights" class="input-box" placeholder="Direct from farm; Rich in nutrients" value="${p && p.highlights ? p.highlights : ''}">
        `;
    }

    function openWalletModal(u) {
        editId = u.id;
        document.getElementById('modal-overlay').style.display = 'flex';
        document.getElementById('modal-title').innerText = "Adjust Customer Wallet: " + u.name;
        document.getElementById('modal-body').innerHTML = `<label>Current Wallet Balance (₹)</label><input type="number" id="m-wallet" class="input-box" value="${u.wallet}">`;
    }

    function viewOrderDetails(id) {
        let o = globalOrders.find(x => x.id == id);
        document.getElementById('modal-overlay').style.display = 'flex';
        document.getElementById('modal-title').innerText = "SEVZO Order #" + o.id + " Details";
        document.getElementById('modal-body').innerHTML = `
            <div style="font-size:13px; line-height:1.6; background:#f8fafc; padding:15px; border-radius:12px; border:1px solid #cbd5e1;">
                <b>Deliver To:</b> ${o.customer_name}<br>
                <b>Full Address:</b> ${o.house_no ? o.house_no : ''}, ${o.full_address}<br>
                <b>Landmark:</b> ${o.landmark ? o.landmark : 'N/A'}<br>
                <b>Pincode:</b> ${o.pincode ? o.pincode : 'N/A'}<br>
                <b>Customer Email:</b> ${o.email ? o.email : 'N/A'}<br>
                <b>Payment Method:</b> ${o.payment_method}<br>
                <hr style="margin:10px 0; border:0; border-top:1px solid #e2e8f0;">
                <b>Security Verification PIN:</b> <span style="font-size:18px; color:var(--secondary); font-weight:bold; letter-spacing:1px;">${o.delivery_pin}</span>
            </div>
        `;
    }

    async function saveData() {
        let res;
        if(currentTab === 'products') {
            res = await req('save_product', {
                id: editId, 
                name: document.getElementById('m-name').value, 
                price: document.getElementById('m-price').value,
                image_url: document.getElementById('m-img').value, 
                category: document.getElementById('m-cat').value, 
                available_pincodes: document.getElementById('m-pin').value,
                brand: document.getElementById('m-brand').value,
                description: document.getElementById('m-desc').value,
                highlights: document.getElementById('m-highlights').value
            });
        } else if(document.getElementById('m-wallet')) {
            res = await req('update_wallet', { id: editId, wallet: document.getElementById('m-wallet').value });
        }
        if(res.status === 'success') { 
            closeModal(); 
            fetchData(currentTab); 
        } else {
            alert("Error: " + res.message);
        }
    }

    async function updateStatus(id, status) { 
        const res = await req('update_order_status', { id: id, order_status: status }); 
        if (res.status === 'success') {
            loadDashboard();
        }
    }

    async function assignRider(orderId, riderId, currentStatus) {
        // If rider is selected, set status to Out for Delivery automatically for consistency
        let status = currentStatus;
        if (riderId !== "") {
            status = "Out for Delivery";
        }
        const res = await req('update_order_status', { id: orderId, order_status: status, delivery_partner_id: riderId });
        if (res.status === 'success') {
            alert("Rider assignment updated!");
            fetchData('orders');
            loadDashboard();
        }
    }

    async function deleteItem(type, id) { 
        if(confirm("Are you sure you want to delete this product?")) { 
            await req('delete_item', { id: id, type: type }); 
            fetchData('products'); 
        } 
    }
    
    function closeModal() { document.getElementById('modal-overlay').style.display = 'none'; }
    
    window.onload = () => {
        if (localStorage.getItem('sevzo_admin_logged') === 'true') {
            loadDashboard();
        }
    };
</script>
</body>
</html>
