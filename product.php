<?php
require_once "db.php";

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($productId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
    } catch (\Exception $e) {
        // Fallback or database error
    }
}

if (!$product) {
    // Redirect to home if product is invalid
    header("Location: index.html");
    exit;
}

// Prepare image gallery
$imagesList = [];
if (!empty($product['images'])) {
    $imagesList = array_map('trim', explode(';', $product['images']));
}
if (!empty($product['image_url'])) {
    array_unshift($imagesList, trim($product['image_url']));
}
$imagesList = array_unique(array_filter($imagesList));

// If still empty, add a placeholder
if (empty($imagesList)) {
    $imagesList[] = 'https://via.placeholder.com/250?text=No+Product+Image';
}

$reviews = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM product_reviews WHERE product_id = ? AND status = 'Approved' ORDER BY id DESC");
    $stmt->execute([$productId]);
    $reviews = $stmt->fetchAll();
} catch (\Exception $e) {}

$stock = isset($product['stock']) ? (int)$product['stock'] : 10;
$variations = [];
if (!empty($product['variations'])) {
    $variations = json_decode($product['variations'], true);
    if (!is_array($variations)) {
        $variations = [];
    }
}

$initialPrice = !empty($variations) ? (float)$variations[0]['price'] : (float)$product['price'];
$mrp = Math_round_mrp($initialPrice);
function Math_round_mrp($price) {
    return round($price * 1.15);
}
$discount = 15; // 15% off
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($product['name']); ?> | SEVZO</title>
    
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
            background: rgba(255, 255, 255, 0.95); 
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
        .image-viewer { width: 100%; padding: 25px 20px; background: white; text-align: center; position: relative; border-bottom: 1px solid var(--gray-light); }
        .main-img-container { width: 100%; height: 260px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .main-img { max-width: 85%; max-height: 100%; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.04)); transition: 0.3s; }
        .badge-new { position: absolute; left: 20px; top: 20px; background: var(--secondary); color: white; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; z-index: 10; }

        /* Gallery Thumbnails */
        .gallery-container { display: flex; justify-content: center; gap: 8px; padding: 5px 0; overflow-x: auto; scrollbar-width: none; }
        .gallery-container::-webkit-scrollbar { display: none; }
        .gallery-thumb { width: 50px; height: 50px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; padding: 4px; background: white; transition: 0.2s; flex-shrink: 0; }
        .gallery-thumb.active { border-color: var(--secondary); transform: scale(1.05); box-shadow: 0 4px 10px rgba(255,0,92,0.08); }

        /* --- Product Info --- */
        .p-details { padding: 20px 25px; }
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
        .variation-chip.active { border-color: var(--secondary) !important; background: rgba(255, 0, 92, 0.04) !important; color: var(--secondary) !important; }
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
        <!-- Gallery Slider -->
        <div class="image-viewer">
            <span class="badge-new">FRESH STOCKS</span>
            <div class="main-img-container">
                <img src="<?php echo htmlspecialchars($imagesList[0]); ?>" class="main-img" id="main-img" onerror="this.src='https://via.placeholder.com/250?text=No+Product+Image'">
            </div>
            
            <!-- Gallery Thumbnails -->
            <div class="gallery-container">
                <?php foreach($imagesList as $idx => $imgUrl): ?>
                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" 
                         class="gallery-thumb <?php echo $idx === 0 ? 'active' : ''; ?>" 
                         onclick="changeGalleryImage('<?php echo htmlspecialchars(addslashes($imgUrl)); ?>', this)">
                <?php endforeach; ?>
            </div>
        </div>
 
        <div class="p-details">
            <div class="p-rating">
                <i class="fas fa-star"></i> 4.8 (<?php echo count($reviews) + 12; ?> reviews)
                <?php if ($stock <= 0): ?>
                    <span style="background:#fee2e2; color:#ef4444; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:800; margin-left:10px;"><i class="fas fa-exclamation-triangle"></i> OUT OF STOCK</span>
                <?php else: ?>
                    <span style="background:#d1fae5; color:#065f46; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:800; margin-left:10px;"><i class="fas fa-check-circle"></i> IN STOCK (<?php echo $stock; ?>)</span>
                <?php endif; ?>
            </div>
            <h1 class="p-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="p-qty">Category: <?php echo htmlspecialchars($product['category']); ?></p>
 
            <div class="price-card">
                <span class="current-price">₹<?php echo htmlspecialchars($initialPrice); ?></span>
                <span class="mrp">₹<?php echo $mrp; ?></span>
                <span class="discount-tag"><?php echo $discount; ?>% OFF</span>
                <p style="font-size: 11px; color: #64748b; margin-top:6px; font-weight: 600;">(Inclusive of all taxes)</p>
            </div>

            <?php if (!empty($variations)): ?>
            <div style="margin-top: 15px; margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 800; color: var(--dark); margin-bottom: 8px; text-transform: uppercase;">Select Weight/Size</h3>
                <div style="display:flex; flex-wrap:wrap; gap:8px;" id="variations-container">
                    <?php foreach ($variations as $idx => $v): ?>
                        <div class="variation-chip <?php echo $idx === 0 ? 'active' : ''; ?>" 
                             onclick="selectVariation(this, '<?php echo htmlspecialchars($v['name']); ?>', <?php echo (float)$v['price']; ?>)"
                             style="border: 1.5px solid #cbd5e1; padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight:700; transition:0.2s; background:white;">
                            <?php echo htmlspecialchars($v['name']); ?> - ₹<?php echo htmlspecialchars($v['price']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
 
            <div class="badge-row">
                <div class="badge-box"><i class="fas fa-shipping-fast"></i> 15 Min Delivery</div>
                <div class="badge-box"><i class="fas fa-shield-alt"></i> Quality Assured</div>
            </div>
 
            <h3 class="section-title">Product Description</h3>
            <p class="p-desc-text"><?php echo htmlspecialchars(!empty($product['description']) ? $product['description'] : 'Perfect quality fresh grocery item. Processed and packaged securely to retain high nutrient levels and premium taste.'); ?></p>
 
            <h3 class="section-title">Highlights</h3>
            <div class="highlight-row"><div class="label">Brand</div><div class="value"><?php echo htmlspecialchars(!empty($product['brand']) ? $product['brand'] : 'SEVZO Fresh'); ?></div></div>
            <div class="highlight-row"><div class="label">Category</div><div class="value"><?php echo htmlspecialchars($product['category']); ?></div></div>
            
            <div id="highlights-container">
                <?php
                if (!empty($product['highlights'])) {
                    $lines = explode(';', $product['highlights']);
                    foreach ($lines as $line) {
                        if (trim($line) === '') continue;
                        if (strpos($line, ':') !== false) {
                            $pts = explode(':', $line, 2);
                            echo '<div class="highlight-row"><div class="label">' . htmlspecialchars(trim($pts[0])) . '</div><div class="value">' . htmlspecialchars(trim($pts[1])) . '</div></div>';
                        } else {
                            echo '<div class="highlight-row"><div class="label">Feature</div><div class="value">' . htmlspecialchars(trim($line)) . '</div></div>';
                        }
                    }
                }
                ?>
            </div>
 
            <h3 class="section-title">Customer Reviews</h3>
            <?php if (empty($reviews)): ?>
                <p style="font-size:12px; color:#94a3b8; font-style:italic; margin-bottom:15px;">No reviews yet. Be the first to review this product!</p>
            <?php else: ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-card">
                        <div class="rev-header">
                            <span class="rev-user"><?php echo htmlspecialchars($r['customer_name']); ?></span>
                            <span class="rev-rating" style="color: #f59e0b;"><?php echo str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']); ?></span>
                        </div>
                        <p class="rev-text"><?php echo htmlspecialchars($r['review_text']); ?></p>
                        <small style="font-size:9px; color:#94a3b8;"><?php echo $r['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Write a Review Form -->
            <div style="background: white; border: 1.5px solid var(--gray-light); border-radius: 16px; padding: 20px; margin-top: 25px;">
                <h3 style="font-size: 15px; font-weight: 800; color: var(--dark); margin-bottom: 12px; border-bottom: 1px solid var(--gray-light); padding-bottom: 8px;">Write a Customer Review</h3>
                
                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Your Name</label>
                <input type="text" id="rev-user-name" style="width:100%; padding:12px; margin-top:4px; margin-bottom:12px; border:1.5px solid var(--gray-light); border-radius:10px; outline: none; font-size:14px; font-weight:600; background: #f8fafc;" placeholder="John Doe">
                
                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Rating</label>
                <select id="rev-user-rating" style="width:100%; padding:12px; margin-top:4px; margin-bottom:12px; border:1.5px solid var(--gray-light); border-radius:10px; outline: none; font-size:14px; font-weight:600; background: #f8fafc;">
                    <option value="5">★★★★★ (5 Stars)</option>
                    <option value="4">★★★★☆ (4 Stars)</option>
                    <option value="3">★★★☆☆ (3 Stars)</option>
                    <option value="2">★★☆☆☆ (2 Stars)</option>
                    <option value="1">★☆☆☆☆ (1 Star)</option>
                </select>
                
                <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Review Comment</label>
                <textarea id="rev-user-text" style="width:100%; height:80px; padding:12px; margin-top:4px; margin-bottom:15px; border:1.5px solid var(--gray-light); border-radius:10px; outline: none; font-size:14px; font-weight:600; background: #f8fafc; resize:none;" placeholder="Tell others about your experience..."></textarea>
                
                <button onclick="submitUserReview()" style="width:100%; padding:14px; background:var(--primary); color:white; font-weight:800; border:none; border-radius:10px; cursor:pointer;">Submit Review</button>
            </div>
        </div>
    </div>
 
    <div class="footer-action">
        <div class="footer-price">
            <span style="font-size: 20px; font-weight: 900; color: var(--dark);" id="foot-price-disp">₹<?php echo htmlspecialchars($initialPrice); ?></span>
            <span style="font-size: 11px; color: var(--secondary); font-weight: 800; cursor:pointer;" onclick="window.location.href='cart.php'">VIEW BASKET</span>
        </div>
        <button class="add-to-cart-btn" id="btn-add-to-basket" <?php echo $stock <= 0 ? 'disabled style="background:#cbd5e1; color:#94a3b8; box-shadow:none; cursor:not-allowed;"' : ''; ?> onclick="addToCart()">
            <?php echo $stock <= 0 ? 'Out of Stock' : 'Add to Basket'; ?>
        </button>
    </div>
</div>
 
<script>
    const productId = <?php echo $productId; ?>;
    let selectedVariation = "<?php echo !empty($variations) ? htmlspecialchars($variations[0]['name']) : 'default'; ?>";
    let selectedPrice = <?php echo !empty($variations) ? (float)$variations[0]['price'] : (float)$product['price']; ?>;
    let stockCount = <?php echo $stock; ?>;

    function changeGalleryImage(url, thumbElement) {
        document.getElementById('main-img').src = url;
        document.querySelectorAll('.gallery-thumb').forEach(t => {
            t.classList.remove('active');
        });
        thumbElement.classList.add('active');
    }

    function selectVariation(el, name, price) {
        document.querySelectorAll('.variation-chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        selectedVariation = name;
        selectedPrice = price;
        
        // Update price display
        document.querySelector('.current-price').innerText = '₹' + price;
        document.querySelector('.mrp').innerText = '₹' + Math.round(price * 1.15);
        document.getElementById('foot-price-disp').innerText = '₹' + price;
    }

    function addToCart() {
        if (stockCount <= 0) {
            alert("This item is currently out of stock.");
            return;
        }
        
        let cart = JSON.parse(localStorage.getItem('my_cart')) || {};
        let cartKey = `${productId}_${selectedVariation}`;
        
        // Check stock locally first
        let currentQty = cart[cartKey] || 0;
        if (currentQty >= stockCount) {
            alert("Sorry, only " + stockCount + " items are available in stock.");
            return;
        }
        
        cart[cartKey] = currentQty + 1;
        localStorage.setItem('my_cart', JSON.stringify(cart));
     
        if(confirm("Item added to basket! View checkout page?")) {
            window.location.href = 'cart.php';
        } else {
            window.location.href = 'index.html';
        }
    }

    async function submitUserReview() {
        const name = document.getElementById('rev-user-name').value.trim();
        const rating = document.getElementById('rev-user-rating').value;
        const text = document.getElementById('rev-user-text').value.trim();
        
        if (!name || !text) {
            alert("Please enter your name and comment.");
            return;
        }
        
        try {
            const res = await fetch('reviews_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'submit_review',
                    product_id: productId,
                    customer_name: name,
                    rating: rating,
                    review_text: text
                })
            });
            const d = await res.json();
            if (d.status === 'success') {
                alert("Thank you! Your review has been published.");
                location.reload();
            } else {
                alert("Error: " + d.message);
            }
        } catch(e) {
            alert("Error submitting review.");
    }
</script>
</body>
</html>
