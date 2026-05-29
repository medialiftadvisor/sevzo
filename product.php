<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Product Details | SEVZO</title>
    
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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background: var(--bg); color: var(--dark); display: flex; justify-content: center; }
        
        .app-container { width: 100%; max-width: 480px; background: var(--white); min-height: 100vh; position: relative; padding-bottom: 100px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }

        /* --- Sticky Top Bar --- */
        .top-header { 
            padding: 15px 20px; 
            position: sticky; 
            top: 0; 
            background: rgba(255,255,255,0.92); 
            backdrop-filter: blur(10px);
            z-index: 100; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid var(--gray-light);
        }
        .top-header i { font-size: 20px; cursor: pointer; color: var(--dark); transition: 0.2s; }
        .top-header i:active { transform: scale(0.85); color: var(--primary); }

        /* --- Product Image Section --- */
        .image-viewer { width: 100%; padding: 30px 20px; background: white; text-align: center; position: relative; }
        .main-img { width: 80%; max-height: 260px; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.05)); }
        .badge-new { position: absolute; left: 20px; top: 20px; background: var(--secondary); color: white; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }

        /* --- Product Info --- */
        .p-details { padding: 20px 25px; border-top: 1px solid var(--gray-light); }
        .p-rating { color: #10b981; font-size: 13px; font-weight: 800; margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
        .p-title { font-size: 22px; font-weight: 800; line-height: 1.3; margin-bottom: 5px; color: var(--dark); }
        .p-qty { color: #64748b; font-size: 13px; margin-bottom: 15px; font-weight: 600; }

        .price-card { background: #f8fafc; padding: 20px; border-radius: 16px; margin-bottom: 20px; border: 1px solid var(--gray-light); }
        .current-price { font-size: 26px; font-weight: 900; color: var(--dark); }
        .mrp { text-decoration: line-through; color: #94a3b8; font-size: 15px; margin-left: 8px; font-weight: 600; }
        .discount-tag { color: #10b981; font-size: 15px; font-weight: 800; margin-left: 10px; }
        
        /* --- Offers & Badges --- */
        .badge-row { display: flex; gap: 12px; margin-bottom: 25px; }
        .badge-box { flex: 1; border: 1.5px solid var(--gray-light); padding: 14px 10px; border-radius: 16px; text-align: center; font-size: 11px; font-weight: 800; color: #475569; }
        .badge-box i { font-size: 20px; margin-bottom: 6px; color: var(--primary); display: block; }

        /* --- Highlights Section --- */
        .section-title { font-size: 16px; font-weight: 800; margin-bottom: 12px; margin-top: 10px; border-bottom: 1px solid var(--gray-light); padding-bottom: 8px; color: var(--dark); }
        .highlight-row { display: flex; padding: 8px 0; font-size: 13px; border-bottom: 1px dashed #f1f5f9; }
        .label { width: 120px; color: #64748b; font-weight: 600; }
        .value { flex: 1; font-weight: 700; color: var(--dark); }

        .p-desc-text { font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px; font-weight: 500; }

        /* Reviews Section */
        .review-card { background: #f8fafc; border-radius: 12px; padding: 12px 15px; margin-bottom: 10px; border: 1px solid #f1f5f9; }
        .rev-header { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 12px; font-weight: bold; }
        .rev-user { color: var(--primary); }
        .rev-rating { color: #f59e0b; }
        .rev-text { font-size: 12px; color: #64748b; line-height: 1.4; }

        /* --- Sticky Footer Action --- */
        .footer-action { position: fixed; bottom: 0; width: 100%; max-width: 480px; background: white; padding: 15px 20px 20px; border-top: 1px solid var(--gray-light); display: flex; justify-content: space-between; align-items: center; z-index: 1000; border-radius: 20px 20px 0 0; box-shadow: 0 -8px 25px rgba(0,0,0,0.04); }
        .add-to-cart-btn { background: var(--secondary); color: white; border: none; padding: 16px 30px; border-radius: 14px; font-weight: 800; font-size: 15px; cursor: pointer; flex: 1; margin-left: 20px; transition: 0.2s; box-shadow: 0 6px 15px rgba(255,0,92,0.2); }
        .add-to-cart-btn:active { transform: scale(0.96); }
        .footer-price { display: flex; flex-direction: column; }
    </style>
</head>
<body>
 
<div class="app-container" id="product-page">
    <div class="top-header">
        <i class="fas fa-arrow-left" onclick="history.back()"></i>
        <div style="display:flex; gap:18px;">
            <i class="fas fa-search" onclick="window.location.href='index.html'"></i>
            <i class="fas fa-shopping-basket" onclick="window.location.href='cart.php'"></i>
        </div>
    </div>
 
    <div id="p-content">
        <div class="image-viewer">
            <span class="badge-new">FRESH STOCKS</span>
            <img src="" class="main-img" id="main-img" onerror="this.src='https://via.placeholder.com/250?text=No+Product+Image'">
        </div>
 
        <div class="p-details">
            <div class="p-rating"><i class="fas fa-star"></i> 4.8 (840 reviews)</div>
            <h1 class="p-title" id="p-title">Loading Product...</h1>
            <p class="p-qty">Net weight - 1 unit</p>
 
            <div class="price-card">
                <span class="current-price" id="p-price">₹0</span>
                <span class="mrp" id="p-mrp">₹0</span>
                <span class="discount-tag" id="p-off">0% OFF</span>
                <p style="font-size: 11px; color: #64748b; margin-top:6px; font-weight: 600;">(Inclusive of all taxes)</p>
            </div>
 
            <div class="badge-row">
                <div class="badge-box"><i class="fas fa-shipping-fast"></i> 15 Min Delivery</div>
                <div class="badge-box"><i class="fas fa-shield-alt"></i> Quality Assured</div>
            </div>

            <h3 class="section-title">Product Description</h3>
            <p class="p-desc-text" id="p-desc">Perfect quality fresh grocery item. Processed and packaged securely to retain high nutrient levels and premium taste.</p>
 
            <h3 class="section-title">Highlights</h3>
            <div class="highlight-row"><div class="label">Brand</div><div class="value" id="p-brand">SEVZO Choice</div></div>
            <div class="highlight-row"><div class="label">Category</div><div class="value" id="p-cat">Fresh</div></div>
            <div id="highlights-container"></div>

            <h3 class="section-title">Customer Reviews</h3>
            <div class="review-card">
                <div class="rev-header">
                    <span class="rev-user">Rohan Sharma</span>
                    <span class="rev-rating">★★★★★</span>
                </div>
                <p class="rev-text">Extremely fresh quality! Got delivered in just 10 mins. Superb service by SEVZO.</p>
            </div>
            <div class="review-card">
                <div class="rev-header">
                    <span class="rev-user">Anjali Gupta</span>
                    <span class="rev-rating">★★★★☆</span>
                </div>
                <p class="rev-text">Packaging was sealed perfectly. Recommended for daily orders.</p>
            </div>
        </div>
    </div>
 
    <div class="footer-action">
        <div class="footer-price">
            <span style="font-size: 20px; font-weight: 900; color: var(--dark);" id="f-price">₹0</span>
            <span style="font-size: 11px; color: var(--secondary); font-weight: 800; cursor:pointer;" onclick="window.location.href='cart.php'">VIEW BASKET</span>
        </div>
        <button class="add-to-cart-btn" onclick="addToCart()">Add to Basket</button>
    </div>
</div>
 
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    let currentProduct = null;
 
    window.onload = async () => {
        if(!productId) {
            alert("Product ID is missing.");
            window.location.href = 'index.html';
            return;
        }

        try {
            const res = await fetch('get_products.php');
            const products = await res.json();
            currentProduct = products.find(p => p.id == productId);
     
            if(currentProduct) {
                document.getElementById('p-title').innerText = currentProduct.name;
                document.getElementById('main-img').src = currentProduct.image_url ? currentProduct.image_url : 'https://via.placeholder.com/250?text=No+Product+Image';
                document.getElementById('p-price').innerText = "₹" + currentProduct.price;
                document.getElementById('f-price').innerText = "₹" + currentProduct.price;
                document.getElementById('p-cat').innerText = currentProduct.category;
                document.getElementById('p-brand').innerText = currentProduct.brand ? currentProduct.brand : 'SEVZO Fresh';
                
                if (currentProduct.description) {
                    document.getElementById('p-desc').innerText = currentProduct.description;
                }
                
                // Render Highlights
                if (currentProduct.highlights) {
                    const lines = currentProduct.highlights.split(';');
                    const hCont = document.getElementById('highlights-container');
                    hCont.innerHTML = lines.map(line => {
                        if(line.includes(':')) {
                            const pts = line.split(':');
                            return `<div class="highlight-row"><div class="label">${pts[0].trim()}</div><div class="value">${pts[1].trim()}</div></div>`;
                        } else {
                            return `<div class="highlight-row"><div class="label">Feature</div><div class="value">${line.trim()}</div></div>`;
                        }
                    }).join('');
                }
                
                // MRP calculation (approx 15% extra)
                let mrp = Math.round(currentProduct.price * 1.15);
                document.getElementById('p-mrp').innerText = "₹" + mrp;
                document.getElementById('p-off').innerText = "15% OFF";
            } else {
                alert("Product not found!");
                window.location.href = 'index.html';
            }
        } catch(e) {
            console.error("Error loading product", e);
        }
    };
 
    function addToCart() {
        let cart = JSON.parse(localStorage.getItem('my_cart')) || {};
        cart[productId] = (cart[productId] || 0) + 1;
        localStorage.setItem('my_cart', JSON.stringify(cart));
     
        if(confirm("Item added to basket! View checkout page?")) {
            window.location.href = 'cart.php';
        } else {
            window.location.href = 'index.html';
        }
    }
</script>
</body>
</html>
