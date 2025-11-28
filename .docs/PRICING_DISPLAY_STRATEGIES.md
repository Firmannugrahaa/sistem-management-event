# 💰 Rekomendasi: "Harga Mulai Dari..." Strategy

## 🎯 4 Skenario Pricing Display

### **Skenario 1: Simple Starting Price** ⭐ [RECOMMENDED for MVP]

**Display:**
```
┌─────────────────────────────┐
│ Paket Wedding Intimate      │
├─────────────────────────────┤
│ Mulai dari                  │
│ Rp 25.500.000               │
│                             │
│ *Final price tergantung     │
│  jumlah tamu & add-ons      │
└─────────────────────────────┘
```

**Pros:**
- ✅ Simple & clear
- ✅ Menarik attention dengan starting price rendah
- ✅ Flexibility untuk customize
- ✅ Mudah implementasi

**Cons:**
- ⚠️ Client mungkin kecewa jika final price jauh lebih tinggi

**Use Case:**
- Package dengan variable guest count
- Package yang customizable
- Marketing-oriented (attract clicks)

---

### **Skenario 2: Price Range**

**Display:**
```
┌─────────────────────────────┐
│ Paket Wedding Intimate      │
├─────────────────────────────┤
│ Rp 25jt - Rp 35jt          │
│                             │
│ • 100 tamu: Rp 25jt         │
│ • 150 tamu: Rp 30jt         │
│ • 200 tamu: Rp 35jt         │
└─────────────────────────────┘
```

**Pros:**
- ✅ Transparency tinggi
- ✅ Client tau budget range
- ✅ Reduce price shock

**Cons:**
- ❌ Bisa intimidating if range terlalu lebar
- ❌ Butuh logic calculation complex

**Use Case:**
- Scalable packages (per guest)
- Clear tier pricing
- Corporate/transparent approach

---

### **Skenario 3: Tiered Packages** ⭐⭐ [BEST for Long-term]

**Structure:**
```
PAKET WEDDING INTIMATE

├─ BASIC (100 tamu)
│  Rp 22.500.000
│  ✓ Venue Standard
│  ✓ Catering Standard
│  ✓ Basic Decoration
│
├─ STANDARD (150 tamu) ⭐ POPULAR
│  Rp 30.000.000
│  ✓ Venue Premium
│  ✓ Catering Premium
│  ✓ Enhanced Decoration
│  ✓ Photo & Video
│
└─ PREMIUM (200 tamu)
   Rp 40.000.000
   ✓ Everything in Standard
   ✓ Cinematic Video
   ✓ Professional MUA
   ✓ Live Entertainment
```

**Display di Landing:**
```
┌─────────────────────────────┐
│ Paket Wedding Intimate      │
├─────────────────────────────┤
│ 3 Pilihan Paket             │
│                             │
│ Basic                       │
│ Mulai Rp 22.500.000         │
│                             │
│ Standard ⭐ POPULAR         │
│ Mulai Rp 30.000.000         │
│                             │
│ Premium                     │
│ Mulai Rp 40.000.000         │
└─────────────────────────────┘
```

**Pros:**
- ✅✅ Clear value ladder
- ✅✅ Easy comparison
- ✅✅ Psychology pricing (middle tier paling laku)
- ✅✅ Upselling opportunity
- ✅ Professional approach

**Cons:**
- ⚠️ Butuh create 3 variants per package
- ⚠️ Lebih komplex manage

**Use Case:**
- Mature business
- Want to maximize revenue
- Clear value proposition

---

### **Skenario 4: Per-Unit Pricing**

**Display:**
```
┌─────────────────────────────┐
│ Paket Wedding Intimate      │
├─────────────────────────────┤
│ Mulai dari Rp 250rb/tamu    │
│                             │
│ Min. 100 tamu               │
│ = Rp 25.000.000             │
│                             │
│ Package includes:           │
│ • Catering                  │
│ • Venue                     │
│ • Decoration                │
└─────────────────────────────┘
```

**Pros:**
- ✅ Scalable pricing
- ✅ Easy mental math untuk client
- ✅ Fair & transparent

**Cons:**
- ❌ Beberapa items ga scale (venue, decoration)
- ❌ Butuh complex breakdown calculation

**Use Case:**
- Catering-focused packages
- Corporate events (per pax)

---

## 🎨 Implementasi Rekomendasi

### **Recommendation: Hybrid Approach**

Combine **Skenario 1** (simple "Mulai dari") dengan **tooltip** yang show tiered options:

```blade
<!-- Landing Page Card -->
<div class="package-card">
    <h3>Paket Wedding Intimate</h3>
    
    <!-- Main Price Display -->
    <div class="price-main">
        <span class="starting-text">Mulai dari</span>
        <span class="price-amount">Rp 25.500.000</span>
    </div>
    
    <!-- Price Tiers Dropdown (on hover/click) -->
    <div class="price-tiers hidden">
        <div class="tier">
            <span class="tier-name">Basic (100 pax)</span>
            <span class="tier-price">Rp 22.500.000</span>
        </div>
        <div class="tier popular">
            <span class="tier-name">Standard (150 pax) ⭐</span>
            <span class="tier-price">Rp 30.000.000</span>
        </div>
        <div class="tier">
            <span class="tier-name">Premium (200 pax)</span>
            <span class="tier-price">Rp 40.000.000</span>
        </div>
    </div>
    
    <button>Lihat Detail</button>
</div>
```

### **Database Structure for Tiers:**

**Option A: Same Table (Simple)**
```sql
ALTER TABLE event_packages ADD COLUMN tier_data JSON;

-- Store tiers as JSON
{
  "basic": {
    "name": "Basic Package",
    "price": 22500000,
    "guest_count": 100,
    "features": [...]
  },
  "standard": {
    "name": "Standard Package",
    "price": 30000000,
    "guest_count": 150,
    "is_popular": true,
    "features": [...]
  },
  "premium": {
    "name": "Premium Package",
    "price": 40000000,
    "guest_count": 200,
    "features": [...]
  }
}
```

**Option B: Separate Table (Advanced)**
```sql
CREATE TABLE event_package_tiers (
    id BIGINT PRIMARY KEY,
    event_package_id BIGINT,
    tier_name VARCHAR(50), -- 'basic', 'standard', 'premium'
    display_name VARCHAR(100),
    base_price DECIMAL(12,2),
    guest_count INT,
    is_popular BOOLEAN DEFAULT false,
    features JSON,
    sort_order INT
);
```

---

## 📊 Comparison Matrix

| Skenario | Complexity | Transparency | Sales Impact | Best For |
|----------|-----------|--------------|--------------|----------|
| 1. Starting Price | ⭐ Low | ⭐⭐ Medium | ⭐⭐⭐ High | MVP, Marketing |
| 2. Price Range | ⭐⭐ Medium | ⭐⭐⭐ High | ⭐⭐ Medium | Scalable Events |
| 3. Tiered Packages | ⭐⭐⭐ High | ⭐⭐⭐⭐ Very High | ⭐⭐⭐⭐ Very High | Mature Business |
| 4. Per-Unit Price | ⭐⭐ Medium | ⭐⭐⭐⭐ Very High | ⭐⭐ Medium | Catering-focused |

---

## 🚀 Quick Implementation (Skenario 1)

### **Step 1: Update Model**
Already done! Added `starting_price_text` accessor.

### **Step 2: Update View (2 Options)**

**Option A: Bold "Mulai dari" style:**
```blade
<div class="pricing-box">
    <p class="text-sm text-gray-600 mb-1">Harga Paket</p>
    <div class="flex items-baseline gap-2">
        <span class="text-lg text-gray-700 font-medium">Mulai dari</span>
        <span class="text-3xl font-bold text-primary">
            Rp {{ number_format($package->final_price, 0, ',', '.') }}
        </span>
    </div>
    <p class="text-xs text-gray-500 mt-2">
        *Harga dapat disesuaikan dengan kebutuhan
    </p>
</div>
```

**Option B: Minimalist hover style:**
```blade
<div class="price-container relative group">
    <div class="text-3xl font-bold text-primary">
        Rp {{ number_format($package->final_price, 0, ',', '.') }}
    </div>
    <div class="absolute -top-8 left-0 bg-blue-600 text-white px-3 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition">
        Harga mulai dari (base package)
    </div>
</div>
```

---

## 💡 My Personal Recommendation

**Phase 1 (Now):** Use **Skenario 1** with "Mulai dari"
- Quick to implement
- Good for marketing
- Add tooltip hint

**Phase 2 (3-6 months):** Upgrade to **Skenario 3** (Tiered)
- Create 3 tiers per popular package
- A/B test conversion rates
- Analyze which tier sells most

**Why this approach?**
1. ✅ Start simple, iterate based on data
2. ✅ Get market feedback first
3. ✅ Avoid over-engineering
4. ✅ Can always add tiers later

---

## 📝 Sample Display Code (Ready to Use)

```blade
<!-- Pricing Info with "Mulai dari" -->
<div class="mb-6 p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg">
    @if($package->discount_percentage > 0)
        <!-- Show discount badge -->
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-gray-500 line-through">{{ $package->formatted_base_price }}</span>
            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                HEMAT {{ $package->discount_percentage }}%
            </span>
        </div>
    @endif
    
    <!-- "Mulai dari" Text -->
    <div class="text-sm text-gray-600 mb-1">Harga Paket</div>
    
    <div class="flex items-baseline gap-2">
        <span  class="text-base text-gray-700 font-medium">Mulai dari</span>
        <span class="text-3xl font-bold text-primary">
            Rp {{ number_format($package->final_price, 0, ',', '.') }}
        </span>
    </div>
    
    @if($package->savings > 0)
        <p class="text-sm text-green-600 font-medium mt-2">
            💰 Hemat {{ $package->formatted_savings }}!
        </p>
    @endif
    
    <p class="text-xs text-gray-500 mt-2 italic">
        *Harga final disesuaikan dengan jumlah tamu dan add-ons
    </p>
</div>
```

This gives you:
- ✅ "Mulai dari" text
- ✅ Discount badge
- ✅ Savings highlight
- ✅ Clear disclaimer
- ✅ Professional look

Done! 🎉
