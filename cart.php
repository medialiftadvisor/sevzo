<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Review Basket | SEVZO</title>
    
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
        
        body { background: var(--bg); color: var(--dark); padding-bottom: 120px; display: flex; justify-content: center; }
        
        .app-container { width: 100%; max-width: 480px; background: white; min-height: 100vh; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        /* Premium Header */
        .header { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px); 
            padding: 18px 20px; 
            position: sticky; 
            top: 0; 
            z-index: 100; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            border-bottom: 1px solid var(--gray-light); 
        }
        .header h3 { font-size: 19px; font-weight: 800; color: var(--primary); }
        .header i { font-size: 20px; cursor: pointer; color: var(--primary); transition: 0.2s; }
        .header i:active { transform: scale(0.85); }

        .container { padding: 15px 20px; }

        /* Elegant Cards */
        .card { 
            background: white; 
            border-radius: 20px; 
            padding: 20px; 
            margin-bottom: 18px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
            border: 1.5px solid var(--gray-light);
            animation: fadeInUp 0.4s ease-out;
        }
        
        .card h4 { font-size: 14px; margin-bottom: 15px; color: var(--primary); display: flex; align-items: center; gap: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .card h4 i { color: var(--secondary); font-size: 16px; }

        /* Cart Item Styling */
        .cart-item { display: flex; align-items: center; gap: 12px; padding: 14px 0; border-bottom: 1.5px solid #f8fafc; transition: 0.3s; }
        .cart-item:last-child { border-bottom: none; }
        .item-info { flex: 1; }
        .item-info h5 { font-size: 14px; color: var(--dark); font-weight: 700; }
        .item-info p { font-size: 13px; color: var(--secondary); font-weight: 800; margin-top: 2px; }
        
        .qty-box { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            border: 1.5px solid var(--gray-light); 
            padding: 5px 12px; 
            border-radius: 12px; 
            font-weight: 800; 
            background: #fff;
            color: var(--dark);
        }
        .qty-box span:not(:nth-child(2)) { color: var(--secondary); font-size: 18px; cursor: pointer; padding: 0 4px; }
        .qty-box span:active { transform: scale(0.9); }

        /* Payment Options Animation */
        .pay-option { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            padding: 16px; 
            border: 2px solid var(--gray-light); 
            border-radius: 16px; 
            margin-bottom: 12px; 
            cursor: pointer; 
            transition: 0.2s all ease-in-out;
        }
        .pay-option.active { border-color: var(--secondary); background: #fff0f5; transform: scale(1.01); box-shadow: 0 6px 15px rgba(255, 0, 92, 0.08); }
        .pay-option i { font-size: 20px; width: 24px; text-align: center; }

        /* Bill Animation */
        .bill-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; color: #64748b; font-weight: 600; }
        .grand-total { border-top: 1.5px dashed var(--gray-light); padding-top: 15px; margin-top: 10px; font-weight: 900; font-size: 20px; color: var(--dark); }

        /* Footer & Animated Button */
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            max-width: 480px; 
            background: white; 
            padding: 20px 25px 25px; 
            border-top: 1px solid var(--gray-light); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            z-index: 1000;
            border-radius: 24px 24px 0 0; 
            box-shadow: 0 -8px 25px rgba(0,0,0,0.04);
        }
        .footer p { font-size: 22px; font-weight: 900; color: var(--dark); }
        
        .place-btn { 
            background: linear-gradient(135deg, var(--secondary), #ff4d8d); 
            color: white; 
            border: none; 
            padding: 16px 36px; 
            border-radius: 16px; 
            font-weight: 800; 
            font-size: 15px; 
            cursor: pointer; 
            transition: 0.3s; 
            box-shadow: 0 6px 18px rgba(255, 0, 92, 0.25);
            display: flex; 
            align-items: center; 
            gap: 8px;
        }
        .place-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(255, 0, 92, 0.3); }
        .place-btn:active { transform: translateY(0); }
        .place-btn:disabled { background: #cbd5e1; box-shadow: none; transform: none; color: #94a3b8; cursor: not-allowed; }

        /* Modal dialog settings */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(6px);
            display: none;
            align-items: flex-end;
            justify-content: center;
            z-index: 2000;
        }

        .modal-content {
            background: var(--white);
            width: 100%;
            max-width: 480px;
            padding: 30px 25px;
            border-radius: 30px 30px 0 0;
            text-align: center;
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        }

        .modal-btn {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(59,0,104,0.15);
        }

        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="app-container">
    <div class="header">
        <i class="fas fa-chevron-left" onclick="history.back()"></i>
        <h3>Review Basket</h3>
    </div>

    <div class="container">
        <!-- Empty Cart Container -->
        <div id="empty-cart-container" style="display:none; flex-direction:column; align-items:center; justify-content:center; padding:80px 20px; text-align:center; animation: fadeInUp 0.4s ease-out;">
            <div style="background:#fff0f5; width:95px; height:95px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:20px; border:2.5px dashed var(--secondary);">
                <i class="fas fa-shopping-basket" style="font-size:36px; color:var(--secondary);"></i>
            </div>
            <h2 style="font-weight:900; font-size:20px; color:var(--primary); margin-bottom:8px;">Your Basket is Empty</h2>
            <p style="color:#64748b; font-size:13px; font-weight:600; line-height:1.5; margin-bottom:25px; max-width:85%;">Add some fresh items to your basket to start your ultra-fast 15-minute delivery!</p>
            <button onclick="window.location.href='index.html'" style="background:var(--primary); color:white; border:none; padding:15px 30px; border-radius:14px; font-weight:800; font-size:14px; cursor:pointer; box-shadow:0 4px 12px rgba(59,0,104,0.15); transition:0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='none'">
                <i class="fas fa-store" style="margin-right:6px;"></i> Shop Fresh Items
            </button>
        </div>

        <div id="checkout-container">
            <!-- Address Card -->
            <div class="card">
                <h4><i class="fas fa-map-marker-alt"></i> Delivering to</h4>
                <div id="addr-text" style="font-size: 13px; color: #475569; line-height: 1.5; font-weight: 600;">
                    <i class="fas fa-spinner fa-spin"></i> Loading address...
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="card">
                <h4><i class="fas fa-shopping-bag"></i> Order Summary</h4>
                <div id="cart-list"></div>
            </div>

            <!-- Payment Card -->
            <div class="card">
                <h4><i class="fas fa-credit-card"></i> Payment Method</h4>
                <div class="pay-option active" id="opt-wallet" onclick="selectPay('Wallet')">
                    <i class="fas fa-wallet" style="color: #10b981;"></i>
                    <div style="flex:1; font-size:13px; font-weight:600;">
                        <b style="font-size:14px;">SEVZO Wallet</b><br>
                        <span id="wallet-amt" style="color:#64748b;">Loading balance...</span>
                    </div>
                    <i class="fas fa-check-circle check-icon" id="wallet-check" style="color: var(--secondary);"></i>
                </div>
                <div class="pay-option" id="opt-upi" onclick="selectPay('UPI')">
                    <i class="fas fa-mobile-alt" style="color: #3b82f6;"></i>
                    <div style="flex:1; font-size:13px; font-weight:600;">
                        <b style="font-size:14px;">UPI Apps</b><br>
                        <span style="color:#64748b;">Pay via PhonePe, GPay, Paytm</span>
                    </div>
                    <i class="fas fa-chevron-right" id="upi-arrow" style="color: #cbd5e1;"></i>
                </div>
                <div id="insufficient-warning" style="display:none; color:var(--secondary); font-size:12px; font-weight:700; margin-top:8px; text-align:center;">
                    ⚠️ Insufficient balance! Please recharge in your Account section.
                </div>
            </div>

            <!-- Bill Details Card -->
            <div class="card">
                <div class="bill-row"><span>Items Subtotal</span><span id="sub-total">₹0</span></div>
                <div class="bill-row"><span>Delivery Partner Fee</span><span style="color: #10b981;">₹25</span></div>
                <div class="bill-row"><span>Order Handling Fee</span><span>₹5</span></div>
                <div class="bill-row grand-total"><span>Total Payable</span><span id="grand-total">₹0</span></div>
            </div>
        </div>
    </div>

    <!-- Sticky Footer -->
    <div class="footer">
        <div>
            <small style="color:#64748b; font-size:11px; font-weight: 700; text-transform: uppercase;">Payable amount</small>
            <p id="foot-total">₹0</p>
        </div>
        <button class="place-btn" id="main-place-btn" onclick="handlePlaceOrder()">
            Place Order <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- Mock UPI QR Code Checkout Modal -->
<div id="upi-modal" class="modal-overlay" style="align-items: center; z-index:3000;">
    <div class="modal-content" style="border-radius:24px; max-width:400px; padding:30px; position:relative; margin-bottom:0;">
        <h3 style="font-weight:900; color:var(--primary); margin-bottom:5px;">Scan UPI QR Code</h3>
        <p style="font-size:12px; color:#64748b; font-weight:600; margin-bottom:15px;">Amount Payable: <b style="color:var(--secondary);" id="upi-modal-amount">₹0</b></p>
        
        <!-- Dynamic QR Code Container -->
        <div style="background:#f8fafc; border:1.5px solid var(--gray-light); padding:15px; border-radius:18px; display:inline-block; margin-bottom:15px;">
            <img id="upi-qr-img" src="" style="width:190px; height:190px; object-fit:contain; border-radius:8px;">
        </div>
        
        <p style="font-size:12px; color:#64748b; font-weight:700; margin-bottom:5px;">Merchant UPI VPA: <span style="color:var(--primary);">my1504@ybl</span></p>
        
        <div style="background:#fff9e6; color:#b45309; padding:8px 12px; border-radius:10px; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:6px; margin-bottom:20px;">
            <i class="fas fa-spinner fa-spin"></i> Checking confirmation: <span id="upi-timer">02:00</span>
        </div>

        <div style="display:flex; gap:10px;">
            <button onclick="verifyUpiPayment()" class="modal-btn" style="flex:1; padding:12px; font-size:13px; background:var(--primary);">I Have Paid</button>
            <button onclick="closeUpiModal()" class="modal-btn" style="flex:1; padding:12px; font-size:13px; background:#cbd5e1; color:#475569; box-shadow:none;">Cancel</button>
        </div>
    </div>
</div>

<script>
    let products = [];
    let cart = JSON.parse(localStorage.getItem('my_cart')) || {};
    let user = JSON.parse(localStorage.getItem('quickmart_user'));
    let selectedMethod = 'Wallet';
    let dbAddress = null;
    let walletBalance = 0;
    let payableGrandTotal = 0;
    let upiTimerInterval = null;

    window.onload = async () => {
        if(!user) { 
            alert("Please login first to place an order.");
            window.location.href = 'account.php';
            return; 
        }
        
        fetchLiveWallet();
        fetchLiveAddress();

        try {
            const res = await fetch('get_products.php');
            products = await res.json();
            renderCart();
        } catch(e) { console.error("Error loading products", e); }
    };

    async function fetchLiveWallet() {
        try {
            const res = await fetch('get_wallet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: user.phone })
            });
            const d = await res.json();
            if(d.status === 'success') {
                walletBalance = parseFloat(d.wallet);
                document.getElementById('wallet-amt').innerText = "Balance: ₹" + walletBalance.toFixed(2);
                checkWalletSufficiency();
            }
        } catch(e) { document.getElementById('wallet-amt').innerText = "Sync failed"; }
    }

    async function fetchLiveAddress() {
        try {
            const res = await fetch('get_address.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: user.phone })
            });
            const d = await res.json();
            if(d.status === 'success' && d.address) {
                dbAddress = d.address;
                document.getElementById('addr-text').innerHTML = `
                    <b style="color:var(--dark); font-size: 14px;">${dbAddress.receiver_name}</b><br>
                    ${dbAddress.house_no}, ${dbAddress.full_address}<br>
                    <span style="color:#64748b">Landmark: ${dbAddress.landmark ? dbAddress.landmark : 'N/A'} | Pin: ${dbAddress.pincode}</span>
                `;
                if(Object.keys(cart).length > 0) document.getElementById('main-place-btn').disabled = false;
            } else {
                document.getElementById('addr-text').innerHTML = "<b style='color:var(--secondary)'>No delivery address saved! Please configure your address in the Account section.</b>";
                document.getElementById('main-place-btn').disabled = true;
            }
        } catch(e) { document.getElementById('addr-text').innerText = "Network error loading address"; }
    }

    function renderCart() {
        const list = document.getElementById('cart-list');
        let subtotal = 0;
        let html = '';
        
        if(Object.keys(cart).length === 0) {
            document.getElementById('checkout-container').style.display = 'none';
            document.getElementById('empty-cart-container').style.display = 'flex';
            document.querySelector('.footer').style.display = 'none';
            updateBill(0);
            document.getElementById('main-place-btn').disabled = true;
            return;
        } else {
            document.getElementById('checkout-container').style.display = 'block';
            document.getElementById('empty-cart-container').style.display = 'none';
            document.querySelector('.footer').style.display = 'flex';
        }

        for(let id in cart) {
            let p = products.find(x => x.id == id);
            if(p) {
                let itemTotal = parseFloat(p.price) * cart[id];
                subtotal += itemTotal;
                html += `
                <div class="cart-item">
                    <div class="item-info">
                        <h5>${p.name}</h5>
                        <p>₹${p.price}</p>
                    </div>
                    <div class="qty-box">
                        <span onclick="updateQty(${id}, -1)">−</span>
                        <span>${cart[id]}</span>
                        <span onclick="updateQty(${id}, 1)">+</span>
                    </div>
                </div>`;
            }
        }
        list.innerHTML = html;
        updateBill(subtotal);
    }

    function updateBill(subtotal) {
        let delivery = subtotal > 0 ? 25 : 0;
        let handling = subtotal > 0 ? 5 : 0;
        payableGrandTotal = subtotal + delivery + handling;
        
        document.getElementById('sub-total').innerText = "₹" + subtotal;
        document.getElementById('grand-total').innerText = "₹" + payableGrandTotal;
        document.getElementById('foot-total').innerText = "₹" + payableGrandTotal;
        checkWalletSufficiency();
    }

    function updateQty(id, chg) {
        cart[id] = (cart[id] || 0) + chg;
        if(cart[id] <= 0) delete cart[id];
        localStorage.setItem('my_cart', JSON.stringify(cart));
        renderCart();
    }

    function selectPay(method) {
        selectedMethod = method;
        document.getElementById('opt-wallet').classList.toggle('active', method === 'Wallet');
        document.getElementById('opt-upi').classList.toggle('active', method === 'UPI');
        
        document.getElementById('wallet-check').style.display = method === 'Wallet' ? 'block' : 'none';
        document.getElementById('upi-arrow').className = method === 'UPI' ? 'fas fa-check-circle' : 'fas fa-chevron-right';
        document.getElementById('upi-arrow').style.color = method === 'UPI' ? 'var(--secondary)' : '#cbd5e1';
        
        checkWalletSufficiency();
    }

    function checkWalletSufficiency() {
        const warning = document.getElementById('insufficient-warning');
        const btn = document.getElementById('main-place-btn');
        
        if(Object.keys(cart).length === 0) return;

        if (selectedMethod === 'Wallet') {
            if (walletBalance < payableGrandTotal && payableGrandTotal > 30) {
                warning.style.display = 'block';
                btn.disabled = true;
            } else {
                warning.style.display = 'none';
                if (dbAddress && payableGrandTotal > 30) btn.disabled = false;
            }
        } else {
            warning.style.display = 'none';
            if (dbAddress && payableGrandTotal > 30) btn.disabled = false;
        }
    }

    async function handlePlaceOrder() {
        if(!user) return window.location.href = 'account.php';
        if(!dbAddress) return alert("Please save delivery address in Account tab first.");
        
        if(payableGrandTotal <= 30) return alert("Minimum order amount is ₹30.");

        const btn = document.getElementById('main-place-btn');
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> Placing Order...`;

        const orderItems = [];
        for(let id in cart) {
            let p = products.find(x => x.id == id);
            if(p) orderItems.push({ n: p.name, q: cart[id], p: p.price });
        }

        const payload = {
            name: user.name,
            phone: user.phone,
            address: `${dbAddress.receiver_name}, ${dbAddress.house_no}, ${dbAddress.full_address}, Landmark: ${dbAddress.landmark}, Pin: ${dbAddress.pincode}`,
            total_amount: payableGrandTotal,
            payment_method: selectedMethod,
            lat: dbAddress ? dbAddress.lat : '',
            lng: dbAddress ? dbAddress.lng : '',
            items: orderItems
        };

        try {
            const res = await fetch('place_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const d = await res.json();
            
            if(d.status === 'success') {
                if(selectedMethod === 'UPI') {
                    // Open dynamic QR code modal instead of trying custom app launch
                    btn.disabled = false;
                    btn.innerHTML = `Place Order <i class="fas fa-arrow-right"></i>`;
                    openUpiModal(d.order_id, payableGrandTotal);
                } else {
                    localStorage.removeItem('my_cart');
                    alert("🎉 Order placed successfully! Track your delivery now.");
                    window.location.href = 'account.php?tab=orders';
                }
            } else {
                alert("Order failed: " + d.message);
                btn.disabled = false;
                btn.innerHTML = `Place Order <i class="fas fa-arrow-right"></i>`;
            }
        } catch(e) {
            alert("Network connection issue. Please try again.");
            btn.disabled = false;
            btn.innerHTML = `Place Order <i class="fas fa-arrow-right"></i>`;
        }
    }

    function openUpiModal(orderId, amount) {
        document.getElementById('upi-modal-amount').innerText = "₹" + amount.toFixed(2);
        
        // QR API loading to my1504@ybl merchant UPI ID
        const upiLink = `upi://pay?pa=my1504@ybl&pn=SEVZO&am=${amount}&cu=INR&tn=Order_SEVZO_${orderId}`;
        document.getElementById('upi-qr-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=` + encodeURIComponent(upiLink);
        
        document.getElementById('upi-modal').style.display = 'flex';
        
        let timeRemaining = 120;
        document.getElementById('upi-timer').innerText = "02:00";
        clearInterval(upiTimerInterval);
        upiTimerInterval = setInterval(() => {
            timeRemaining--;
            let mins = Math.floor(timeRemaining / 60);
            let secs = timeRemaining % 60;
            document.getElementById('upi-timer').innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            
            if(timeRemaining <= 0) {
                clearInterval(upiTimerInterval);
                alert("UPI payment session timed out. Checkout marked pending. Complete inside Account tab.");
                window.location.href = 'account.php?tab=orders';
            }
        }, 1000);
    }

    function verifyUpiPayment() {
        clearInterval(upiTimerInterval);
        document.getElementById('upi-modal').style.display = 'none';
        localStorage.removeItem('my_cart');
        alert("🎉 UPI Payment confirmed! Order has been received for 15-minute delivery.");
        window.location.href = 'account.php?tab=orders';
    }

    function closeUpiModal() {
        clearInterval(upiTimerInterval);
        document.getElementById('upi-modal').style.display = 'none';
        alert("Payment canceled. You can complete/re-verify this payment inside your account profile orders section.");
        window.location.href = 'account.php?tab=orders';
    }
</script>
</body>
</html>
