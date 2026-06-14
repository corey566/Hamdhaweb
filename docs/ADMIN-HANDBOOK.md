# Hamdha Clothing — Admin Handbook (Zero to Hero)

This guide is for the person who manages the shop through the **Filament admin panel**. No coding required for day-to-day work.

---

## 1. Access the admin panel

| Item | Value |
|------|--------|
| **Admin URL** | `https://your-domain.com/admin` (local: `http://127.0.0.1:8000/admin`) |
| **Login** | Email + password (created by the site owner / developer) |

**First-time admin account** (developer only, run once on server):

```bash
php artisan make:filament-user
```

After login you see **Hamdha Admin** with sidebar groups: **Catalog**, **Website**, and standalone pages like **Site Settings** / **Homepage**.

**Rule:** Always click **Save** on each page after edits. Most changes go live immediately for customers.

---

## 2. How the website works (big picture)

```
Customer visits site
    → Browses categories / filters products
    → Opens product page
    → Adds to Wish List (browser storage)
    → Orders via WhatsApp (no cart / no checkout)
```

**You control:** products, categories, prices, images, homepage, info pages, footer, WhatsApp number, navigation style, currency toggle, etc.

**You do not control in admin:** payment gateway, shipping labels, inventory sync (not built — this is a catalog + WhatsApp order site).

---

## 3. Admin menu map

### Catalog
| Menu | Purpose |
|------|---------|
| **Categories** | Shop structure (Abayas, Hijab, Plain, Embroidery, …). Drives navbar & collection pages. |
| **Fabrics** | Fabric names shown on products & WhatsApp messages. |
| **Products** | All sellable items — images, price, categories, visibility. |
| **Size Charts** | Size guide images linked to products. |
| **Price Buckets** | Price range filters on listing pages (e.g. Under 10,000). |

### Website
| Menu | Purpose |
|------|---------|
| **Navigation** | Switch navbar Option A vs Option B; preview category tree. |
| **Content Pages** | Delivery, FAQs, Size Guide, Our Story (`/page/...`). |
| **Homepage** | Hero slider, 3-step section, mission block. |
| **Site Settings** | WhatsApp, footer, currency, storefront toggles, announcement bar. |

---

## 4. Recommended setup order (new shop)

Do these in order the first time:

1. **Site Settings → WhatsApp** — correct phone number & message template.  
2. **Site Settings → Social & Contact** — phone, Instagram, Facebook, TikTok.  
3. **Site Settings → Footer** — footer text + links (Our Story, Size Guide, Delivery, FAQs).  
4. **Site Settings → Storefront** — navigation mode (A or B), currency, wishlist, collection layout.  
5. **Catalog → Categories** — confirm tree, rename, reorder (drag rows), hide unused.  
6. **Website → Navigation** — save Option A or B (must match your category plan).  
7. **Catalog → Fabrics** — add all fabric types you use.  
8. **Catalog → Price Buckets** — set filter ranges (optional).  
9. **Catalog → Size Charts** — upload chart images.  
10. **Catalog → Products** — add real products (cover + gallery).  
11. **Website → Content Pages** — proofread Delivery, FAQs, Size Guide, Our Story.  
12. **Homepage** — hero images, titles, mission section.  
13. Open the live site in a private window and test: nav, category, product, WhatsApp button, wishlist.

---

## 5. Categories (shop structure)

**Path:** Catalog → Categories

### Fields
- **Name** — shown in menu and on site.  
- **Slug** — URL part (`plain-abaya` → `/category/plain-abaya`). Auto-filled from name; change carefully after go-live.  
- **Parent Category** — empty = top level (Abayas, Hijab). Set parent = subcategory (Plain under Abayas).  
- **Cover Image** — 4:5 ratio, used on collection tiles if applicable.  
- **Sort order** — lower numbers appear first. **Drag rows** in the list to reorder.  
- **Visible in navigation** — off = hidden from menu (page may still work if linked directly).

### Default tree (after seeding)
**Abayas:** Plain, Embroidery, Beads, Cutwork, Stonework, Haj, Wedding  
**Hijab:** Cotton, Georgette, Printed, Viscose, Bubble  

Rename in admin (e.g. Plain → “Plain Abaya”) anytime.

### Navbar behavior
| Mode | Where to set | What customer sees |
|------|----------------|----------------------|
| **Option A — Flat** | Website → Navigation *or* Site Settings → Storefront | New Arrivals + every subcategory as its own link + All Products |
| **Option B — Hierarchical** (default) | Same | New Arrivals + Abayas ▾ + Hijab ▾ + All Products; hover/accordion for subs |

**Mobile:** Option B uses accordion in the side menu; Option A shows flat links.

---

## 6. Products (core daily task)

**Path:** Catalog → Products → Create / Edit

### Product Information
| Field | Notes |
|-------|--------|
| **Name** | Display title on cards and product page. |
| **Model Number** | Auto-generated on save (e.g. HM-0001). Prefix in Site Settings → Product Settings. |
| **Price** | LKR, required. |
| **Discount price** | Optional; must be less than price. Shows SALE badge. |
| **Description** | Rich text on product page. |
| **Fabric** | Pick existing or create new inline. |
| **Colors / Note** | e.g. “Multiple Options — Confirm Via WhatsApp”. |

### Categories & Tags
- Check **all categories** this product belongs to.  
- **First checked category** = primary (main collection).  
- **Other checked categories** = small tag pills on the product card (e.g. Wedding, Embroidery).

### Images (very important)
| Upload | Use |
|--------|-----|
| **Cover image** | 1080×1350 (4:5). Used on grids, search, and main PDP image. **Always upload.** |
| **Gallery** | Up to 5 extra images. Second image often used as **hover** on product cards. |

Images are processed to WebP automatically.

### Visibility
| Toggle | Effect |
|--------|--------|
| **Visible on website** | Off = hidden everywhere. |
| **Show in Featured** | On = can appear in homepage “Featured designs” and Featured filter (if enabled in settings). |

### Size charts
Attach one or more charts from **Catalog → Size Charts**. They appear on the product page.

### After saving
- View live: `/product/{slug}` (slug from product URL or list).  
- Category page updates automatically when categories are assigned.

---

## 7. Fabrics, size charts, price filters

### Fabrics
**Catalog → Fabrics** — simple list (name only). Used in product form and WhatsApp template `{fabric}`.

### Size charts
**Catalog → Size Charts** — name + one image (4:5). Link charts to products in the product form.

### Price buckets
**Catalog → Price Buckets** — label + min/max LKR. Powers “filter by price” on listing pages. Example:
- Under 5,000 → min 0, max 5000  
- Above 20,000 → min 20000, max empty  

---

## 8. Content pages (info / legal / help)

**Path:** Website → Content Pages

| Default slug | Live URL | Typical use |
|--------------|----------|-------------|
| `our-story` | `/page/our-story` | Brand story |
| `size-guide` | `/page/size-guide` | General sizing text |
| `delivery` | `/page/delivery` | Shipping times |
| `faqs` | `/page/faqs` | Q&A |

### Editable per page
- Title, slug, banner image  
- Main content (rich editor)  
- Extra sections (heading + body + image)  
- **Visible** toggle  
- Sort order (admin list only)

**Footer links** to these pages are edited separately in **Site Settings → Footer** (label + URL path like `/page/delivery`).

---

## 9. Homepage

**Path:** Website → Homepage → Save

| Tab | Controls |
|-----|----------|
| **Hero** | Title, subtitle, text, slider images, CTA button text/URL, visible toggle |
| **3-Step Customization** | Section title + 3 steps (number, title, description) |
| **Mission** | Title, rich content, image, CTA, visible toggle |

**Featured products** on homepage come from products marked **Featured** in Catalog → Products (not from this page).

---

## 10. Site settings (store-wide)

**Path:** Site Settings

### Storefront tab
- Site title, shipping announcement (top bar)  
- Navigation layout (Option A / B)  
- Currency switcher, auto-detect, GBP rate  
- Wish list on/off  
- Homepage marquee links on/off  
- “Featured designs” filter on/off  
- Collection layout: tabs vs two columns (All Products / New Arrivals)  
- Product listing tagline  

### WhatsApp tab
- **Number:** country code, no `+` (e.g. `94777626013`)  
- **Template placeholders:** `{model}`, `{name}`, `{price}`, `{fabric}`, `{url}`  

Used on: product page button, wishlist “order all”, etc.

### Announcement bar
Rotating messages in top header (desktop) / ticker (mobile).

### Social & Contact
Phone + social URLs. Phone shows in footer and header.

### Footer
- Tagline  
- **Information links** (Our Story, Size Guide, …)  
- **Customer care links** (Delivery, FAQs, Contact — URL can be `/page/...` or WhatsApp link)

### Product settings
- Model prefix (HM)  
- How many products on **New Arrivals** page  

---

## 11. What customers see (storefront map)

| Page | URL | Driven by |
|------|-----|-----------|
| Home | `/` | Homepage manager + featured products |
| New Arrivals | `/new-arrivals` | Latest visible products (count in settings) |
| All Products | `/products` | All visible products + filters |
| Category | `/category/{slug}` | Category + assigned products |
| Product | `/product/{slug}` | Product record |
| Search | `/search?q=...` | Product names |
| CMS page | `/page/{slug}` | Content Pages |
| Wishlist | Header heart | Browser only; “Order via WhatsApp” sends list |

**Filters on listing pages:** collection (Abayas/Hijab), product type (subcategories), fabric, price bucket, featured (if enabled).

---

## 12. Daily / weekly workflows

### Add a new design
1. Catalog → Products → Create  
2. Fill name, price, fabric, description  
3. Upload **cover** + gallery  
4. Assign categories (first = primary)  
5. Toggle visible + featured if needed  
6. Save → open live product link → test WhatsApp button  

### Run a sale
1. Edit product → set **discount price**  
2. Save → SALE badge appears automatically  

### Hide sold-out / draft
Turn off **Visible on website** (do not delete unless permanent).  

### Change menu order
Catalog → Categories → drag rows → save order.  

### Switch navbar style
Website → Navigation → Option A or B → Save.  

### Update delivery times
Website → Content Pages → Delivery → edit → Save.  

---

## 13. Troubleshooting

| Problem | Check |
|---------|--------|
| Product not on site | Visible toggle on? Categories assigned? |
| Wrong tag on card | Re-order categories — **first** checked = primary |
| Menu item missing | Category **Visible in navigation**? Parent/child correct? |
| 404 on info page | Slug matches footer URL? Page **Visible**? |
| WhatsApp wrong number | Site Settings → WhatsApp → Save |
| Prices wrong currency | Site Settings → currency rate & toggles |
| Images blurry | Use 1080×1350 cover; avoid tiny uploads |
| Changes not showing | Hard refresh (Ctrl+F5); clear browser cache |
| Admin can’t login | Ask developer to reset user or run `make:filament-user` |

---

## 14. Quick reference — who edits what

| Customer sees | Edit in admin |
|---------------|----------------|
| Top bar text | Site Settings → Announcement / shipping text |
| Logo & menu | Categories + Navigation mode |
| Product grid | Products + Categories |
| Product detail | Products |
| Size table image | Size Charts → link on Product |
| Footer links | Site Settings → Footer |
| Delivery / FAQ text | Content Pages |
| Homepage hero | Homepage |
| Order button message | Site Settings → WhatsApp |

---

## 15. Safety tips

- Do not delete categories that have products without reassigning products first.  
- Do not change **slugs** on live products/categories unless you set up redirects (developer task).  
- Keep one **cover image** per product — it is the face of the brand on Instagram-style grids.  
- Test WhatsApp from a real phone after changing the number.  

---

*Document version: Hamdha Web — Filament admin. For technical/server issues, contact your developer (Orianwave).*
