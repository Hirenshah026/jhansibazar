@extends('front_layout.main')
@push('css_or_link')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <style>
        /* ─── ORIGINAL CSS (UNCHANGED) ─── */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Nunito', sans-serif }
        .fd { font-family: 'Baloo 2', sans-serif }
        body { background: #F4F6FF; min-height: 100vh }

        .inp {
            width: 100%; background: #fff; border: 1.5px solid #C7D7FF;
            border-radius: 12px; padding: 12px 14px; font-size: 14px;
            color: #1e293b; outline: none; transition: all .2s
        }
        .inp:focus { border-color: #3B5BDB; box-shadow: 0 0 0 3px rgba(59,91,219,.1) }

        .lbl {
            font-size: 11px; font-weight: 800; color: #475569;
            text-transform: uppercase; letter-spacing: .6px;
            margin-bottom: 6px; display: flex; align-items: center; gap: 5px
        }
        .sec {
            background: #fff; border-radius: 18px; padding: 16px;
            border: 1.5px solid #C7D7FF; margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(59,91,219,.06)
        }
        .sec-title {
            font-size: 13px; font-weight: 800; color: #3B5BDB;
            margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
            padding-bottom: 10px; border-bottom: 1.5px solid #E8EDFF
        }
        .chip {
            border: 1.5px solid #C7D7FF; border-radius: 20px; padding: 7px 14px;
            font-size: 12px; font-weight: 700; cursor: pointer; transition: all .15s;
            background: #fff; color: #475569; white-space: nowrap
        }
        .chip.on { background: #3B5BDB; color: #fff; border-color: #3B5BDB }

        /* ─── btn-p: now flex so spinner + label work ─── */
        .btn-p {
            background: #3B5BDB; color: #fff; border: none;
            border-radius: 13px; font-weight: 800; cursor: pointer; transition: all .15s;
            display: flex; align-items: center; justify-content: center; gap: 8px
        }
        .btn-p:hover:not(:disabled) { background: #2f4ec4 }
        .btn-p:disabled { opacity: .6; cursor: not-allowed }

        .toggle {
            width: 44px; height: 24px; border-radius: 12px;
            background: #CBD5E1; position: relative; transition: background .2s
        }
        .toggle.on { background: #3B5BDB }
        .toggle::after {
            content: ''; position: absolute; top: 3px; left: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #fff; transition: transform .2s
        }
        .toggle.on::after { transform: translateX(20px) }

        .ai-locked {
            background: linear-gradient(135deg, #1e1b4b, #3730a3);
            border-radius: 16px; padding: 14px; color: white; position: relative; overflow: hidden
        }
        .form-tab {
            padding: 9px 16px; background: transparent; font-weight: 700;
            font-size: 13px; cursor: pointer; color: #64748b;
            border-bottom: 2.5px solid transparent; border: none; white-space: nowrap
        }
        .form-tab.active { color: #3B5BDB; border-bottom: 2.5px solid #3B5BDB }

        .chp {
            border: 1.5px solid #C7D7FF; border-radius: 20px; padding: 7px 14px;
            font-size: 12px; font-weight: 700; cursor: pointer; transition: all .15s;
            background: #fff; color: #475569; user-select: none; white-space: nowrap
        }
        .chp:hover { border-color: #3B5BDB; color: #3B5BDB; background: #EEF2FF }
        .chp.on { background: #3B5BDB; color: #fff; border-color: #3B5BDB }

        .cat-field {
            background: #FFFBEB; border-radius: 14px; padding: 12px;
            border: 1.5px solid #FDE68A; margin-bottom: 10px
        }
        .cat-field-title {
            font-size: 10px; font-weight: 800; color: #B45309;
            text-transform: uppercase; letter-spacing: .6px;
            margin-bottom: 8px; display: flex; align-items: center; gap: 4px
        }

        /* ─── SPINNER (new) ─── */
        .btn-spinner {
            display: none; width: 18px; height: 18px; flex-shrink: 0;
            border: 2.5px solid rgba(255,255,255,.35); border-top-color: #fff;
            border-radius: 50%; animation: _spin .65s linear infinite
        }
        @keyframes _spin { to { transform: rotate(360deg) } }
        .btn-p.loading       .btn-spinner,
        .crop-confirm.loading .btn-spinner { display: inline-block }
        .btn-p.loading       .btn-label,
        .crop-confirm.loading .btn-label   { opacity: .75 }

        /* ─── PHOTO SLOTS (new) ─── */
        .photo-slot {
            position: relative; border-radius: 12px; overflow: hidden;
            background: #EEF2FF; border: 2px dashed #93C5FD;
            aspect-ratio: 1/1; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: all .15s
        }
        .photo-slot:hover { border-color: #3B5BDB; background: #E0E7FF }
        .photo-slot.filled { border-style: solid; border-color: #22C55E }
        .photo-slot img { width: 100%; height: 100%; object-fit: cover }
        .slot-plus { font-size: 24px; color: #93C5FD; transition: color .15s }
        .photo-slot:hover .slot-plus { color: #3B5BDB }
        .slot-edit-btn {
            position: absolute; bottom: 4px; right: 4px;
            background: rgba(59,91,219,.85); border: none; border-radius: 8px;
            width: 26px; height: 26px; color: white; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 5; transition: background .15s
        }
        .slot-edit-btn:hover { background: #3B5BDB }
        .webp-dot {
            position: absolute; top: 4px; left: 4px;
            background: #16A34A; color: white; font-size: 8px; font-weight: 800;
            padding: 2px 6px; border-radius: 8px; letter-spacing: .3px;
            z-index: 5; pointer-events: none
        }

        /* ─── CROP MODAL (new) ─── */
        #cropModal {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.88); backdrop-filter: blur(5px);
            flex-direction: column; align-items: center;
            justify-content: flex-start; overflow-y: auto
        }
        #cropModal.open { display: flex }
        .crop-inner {
            background: #1e293b; width: 100%; max-width: 480px;
            min-height: 100vh; display: flex; flex-direction: column
        }
        .crop-header {
            background: #3B5BDB; padding: 13px 16px;
            display: flex; align-items: center; justify-content: space-between; flex-shrink: 0
        }
        .crop-header-text { color: white; font-family: 'Baloo 2',sans-serif; font-size: 15px; font-weight: 700 }
        .crop-header-sub  { color: rgba(255,255,255,.65); font-size: 11px; margin-top: 1px }
        .crop-x {
            background: rgba(255,255,255,.18); border: none; border-radius: 50%;
            width: 32px; height: 32px; color: white; font-size: 16px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: background .15s
        }
        .crop-x:hover:not(:disabled) { background: rgba(255,255,255,.32) }
        .crop-x:disabled { opacity: .4; cursor: not-allowed }
        .crop-canvas-wrap {
            flex: 1; background: #0f172a; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            min-height: 260px; max-height: 380px
        }
        #cropImg { display: block; max-width: 100%; max-height: 380px }
        .crop-bar { background: #1e293b; padding: 12px 16px; flex-shrink: 0 }
        .ratio-row { display: flex; gap: 7px; margin-bottom: 11px; flex-wrap: wrap }
        .ratio-btn {
            background: rgba(255,255,255,.07); border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 20px; padding: 5px 13px; font-size: 11px; font-weight: 700;
            color: rgba(255,255,255,.5); cursor: pointer; transition: all .15s
        }
        .ratio-btn.on { background: rgba(59,91,219,.3); border-color: #3B5BDB; color: #93C5FD }
        .tool-row { display: flex; gap: 8px; justify-content: center; margin-bottom: 11px }
        .tool-btn {
            background: rgba(255,255,255,.07); border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 11px; width: 42px; height: 42px; color: white; font-size: 17px;
            cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s
        }
        .tool-btn:hover { background: rgba(59,91,219,.25); border-color: #3B5BDB }
        .webp-note { text-align: center; margin-bottom: 10px; font-size: 11px; color: rgba(255,255,255,.4) }
        .webp-badge-g {
            background: #16A34A; color: white; font-size: 10px; font-weight: 800;
            padding: 2px 8px; border-radius: 10px; letter-spacing: .3px
        }
        .crop-action-row { display: grid; grid-template-columns: 1fr 2fr; gap: 10px }
        .crop-cancel {
            background: transparent; border: 1.5px solid rgba(255,255,255,.18);
            border-radius: 13px; padding: 12px; color: rgba(255,255,255,.55);
            font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'Nunito',sans-serif; transition: all .15s
        }
        .crop-cancel:hover:not(:disabled) { border-color: rgba(255,255,255,.4); color: white }
        .crop-cancel:disabled { opacity: .4; cursor: not-allowed }
        .crop-confirm {
            background: #3B5BDB; border: none; border-radius: 13px; padding: 12px;
            color: white; font-size: 14px; font-weight: 800; cursor: pointer;
            font-family: 'Baloo 2',sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 7px; transition: all .15s
        }
        .crop-confirm:hover:not(:disabled) { background: #2f4ec4; box-shadow: 0 6px 18px rgba(59,91,219,.4) }
        .crop-confirm:disabled { opacity: .55; cursor: not-allowed }
    </style>
@endpush

@section('content')
<div style="max-width:480px;margin:0 auto;min-height:100vh">

    <!-- TOP BAR (unchanged) -->
    <div style="background:#3B5BDB;padding:13px 16px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50">
        <button onclick="window.history.back()" style="color:#fff;background:none;border:none;font-size:20px">←</button>
        <p class="fd" style="color:#fff;font-size:16px;font-weight:800">Item Add Karo</p>
        <button onclick="switchTab(null,'preview')" style="background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);border-radius:20px;padding:5px 12px;color:#fff;font-size:11px">👁 Preview</button>
    </div>

    <!-- SHOP BAR (unchanged) -->
    <div style="background:#EEF2FF;border-bottom:1.5px solid #C7D7FF;padding:10px 16px;display:flex;align-items:center;gap:10px">
        <div style="width:36px;height:36px;background:#3B5BDB;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px" id="shopEmojiDisplay1" ><img src="{{ url('storage/' . (Session::get('shopuser')->photos ?? 'lk')) }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Session::get('shopuser')->shop_name ?? 'N/A') }}&background=random'"></div>
        <div style="flex-grow:1">
            <p style="font-size:12px;font-weight:800;color:#3B5BDB" id="shopNameDisplay">{{ Session::get('shopuser')->shop_name ?? 'N/A' }}</p>
            <p style="font-size:10px;color:#64748b" id="shopCatDisplay">{{ Session::get('shopuser')->address ?? 'N/A' }}</p>
        </div>
        <select onchange="changeShopType(this.value)" style="background:#fff;border:1.5px solid #C7D7FF;border-radius:8px;padding:5px;font-size:11px;font-weight:700" class="hidden">
            <option value="bakery">🎂 Bakery</option>
            <option value="shoes">👟 Shoes</option>
            <option value="cloth">👗 Cloth</option>
            <option value="salon">✂️ Salon</option>
        </select>
    </div>

    <!-- TABS (unchanged) -->
    <div style="display:flex;border-bottom:1.5px solid #E2E8F0;background:#fff;overflow-x:auto">
        <button class="form-tab active" id="btn-basic"   onclick="switchTab(this,'basic')">📝 Basic</button>
        <button class="form-tab"        id="btn-photos"  onclick="switchTab(this,'photos')">📸 Photos</button>
        <button class="form-tab"        id="btn-extras"  onclick="switchTab(this,'extras')">⚙️ Details</button>
        <button class="form-tab"        id="btn-preview" onclick="switchTab(this,'preview')">👁 Preview</button>
    </div>

    <!-- TAB: BASIC (unchanged structure, button gets spinner markup) -->
    <div id="tab-basic" style="padding:16px;padding-bottom:80px">
        <div class="sec">
            <p class="sec-title">📋 Item Information</p>
            <p class="lbl">Item Ka Naam *</p>
            <input id="itemName" class="inp" type="text" placeholder="Jaise: Chocolate Cake" style="margin-bottom:12px">
            <p class="lbl">MRP Price *</p>
            <input id="mrpPrice" class="inp" type="number" placeholder="₹" style="margin-bottom:12px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;cursor:pointer" onclick="toggleDiscount()">
                <div class="toggle" id="discToggle"></div>
                <p style="font-size:13px;font-weight:700">Discount Dena Hai?</p>
            </div>
            <div id="discSection" style="display:none">
                <input id="discPrice" class="inp" type="number" placeholder="Sale Price ₹">
            </div>
        </div>

        <div class="sec">
            <p class="sec-title">🏷️ Category Selection</p>
            <div style="display:flex;gap:7px;flex-wrap:wrap" id="catChips">
                @forelse ($categories as $cate)
                    <div class="chip">{{ucwords($cate->name??'na')}}</div>
                @empty
                    No Category
                @endforelse
            </div>
        </div>

        <div class="sec salon_div" style="display:none">
            <p class="sec-title">⚙️ Unisex Salon Ke Khaas Details</p>
            <div class="cat-field">
                <p class="cat-field-title">⏱️ Service Duration</p>
                <input class="inp" type="number" placeholder="Jaise: 30 min, 1 hour, 2 hours..." name="service_duration" id="service_duration" style="font-size:13px">
            </div>
            <div class="cat-field">
                <p class="cat-field-title">👥 For Whom</p>
                <div style="display:flex;gap:6px">
                    <button class="chp on" onclick="setStock(this,'women')">👩 Women</button>
                    <button class="chp"    onclick="setStock(this,'men')">👨 Men</button>
                    <button class="chp"    onclick="setStock(this,'kids')">👶 Kids</button>
                    <button class="chp"    onclick="setStock(this,'unisex')">🧑 Unisex</button>
                </div>
                <input type="hidden" id="selected_cat" name="salon_cat" value="women">
            </div>
        </div>

        {{-- ── BASIC NEXT — with spinner ── --}}
        <button id="btnBasicNext" class="btn-p" style="width:100%;padding:16px;font-size:15px;margin-top:16px"
                onclick="saveStep('basic','photos')">
            <span class="btn-spinner"></span>
            <span class="btn-label">Next : Photos Daalo 📸</span>
        </button>
    </div>

    <!-- TAB: PHOTOS — grid replaced with crop-enabled slots -->
    <div id="tab-photos" style="display:none;padding:16px;padding-bottom:80px">

        <div style="background:#ECFDF5;border:1px solid #D1FAE5;padding:12px;border-radius:14px;margin-bottom:14px" class="hidden">
            <p style="font-size:11px;font-weight:800;color:#16A34A">🆓 Free Image Library</p>
            <div id="freeImageRow" style="display:flex;gap:8px;overflow-x:auto;padding:8px 0 4px"></div>
        </div>

        <div class="sec">
            <p class="sec-title">
                📸 Photo Upload
                <span style="margin-left:auto;background:#ECFDF5;color:#16A34A;font-size:9px;font-weight:800;padding:2px 8px;border-radius:10px;border:1px solid #86EFAC">Auto WebP ✓</span>
            </p>
            <p style="font-size:11px;color:#64748b;margin-bottom:10px">
                Photo select karo → Crop karo →
                <strong style="color:#16A34A">WebP</strong> mein save hoga automatically
            </p>
            {{-- 3 crop-enabled slots ──--}}
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px" id="photoGrid"></div>
        </div>

        <div class="ai-locked" style="margin-bottom:14px">
            <p class="fd" style="font-size:14px;font-weight:700">🤖 AI Image Generator</p>
            <p style="font-size:10px;opacity:.7;margin-top:2px">Coming Soon: Type karke photo banayein!</p>
        </div>

        {{-- ── PHOTOS NEXT — with spinner ── --}}
        <button id="btnPhotosNext" class="btn-p" style="width:100%;padding:16px;font-size:15px"
                onclick="saveStep('photos','extras')">
            <span class="btn-spinner"></span>
            <span class="btn-label">Next: Details Daalo ⚙️</span>
        </button>
    </div>

    <!-- TAB: EXTRAS (unchanged) -->
    <div id="tab-extras" style="display:none;padding:16px;padding-bottom:80px">
        <div id="catSpecificFields"></div>
        <div class="sec">
            <p class="sec-title">🔍 Tags</p>
            <input id="tags" class="inp" type="text" placeholder="comma se alag karein...">
        </div>
        {{-- ── EXTRAS NEXT — with spinner ── --}}
        <button id="btnExtrasNext" class="btn-p" style="width:100%;padding:16px;font-size:15px"
                onclick="saveStep('extras','preview')">
            <span class="btn-spinner"></span>
            <span class="btn-label">Next: Preview Check Karo 👁</span>
        </button>
    </div>

    <!-- TAB: PREVIEW (unchanged) -->
    <div id="tab-preview" style="display:none;padding:16px;padding-bottom:80px">
        <div style="background:white;border-radius:18px;overflow:hidden;border:1.5px solid #C7D7FF;margin-bottom:20px">
            <div id="prevPhotoArea" style="height:180px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;font-size:50px">🎂</div>
            <div style="padding:16px">
                <h3 id="vName" class="fd" style="font-size:20px;font-weight:800;color:#1e293b">Item Name</h3>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap">
                    <p id="vPrice" style="color:#3B5BDB;font-weight:900;font-size:22px"></p>
                    <p id="vMrp"   style="color:#94a3b8;font-weight:700;font-size:15px;text-decoration:line-through;display:none"></p>
                    <span id="vBadge" style="background:#FEE2E2;color:#DC2626;font-size:11px;font-weight:800;padding:3px 9px;border-radius:20px;display:none"></span>
                </div>
                <p id="vDesc" style="color:#64748b;font-size:13px;margin-top:8px"></p>
            </div>
        </div>
        {{-- ── GO LIVE — with spinner ── --}}
        <button id="btnGoLive" class="btn-p" style="width:100%;padding:16px;font-size:15px;background:#16A34A"
                onclick="finalizeLive()">
            <span class="btn-spinner"></span>
            <span class="btn-label">Confirm & Go Live 🚀</span>
        </button>
    </div>

    <!-- SUCCESS OVERLAY (unchanged) -->
    <div id="successOverlay" style="display:none;position:fixed;inset:0;background:white;z-index:200;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:24px">
        <div style="font-size:64px;margin-bottom:16px">✅</div>
        <h2 class="fd" style="font-size:26px;font-weight:900;color:#1e293b">Mubarak Ho!</h2>
        <p style="color:#64748b;margin-top:6px">Aapka item Jhansi Bazaar par live hai.</p>
        <button class="btn-p" style="padding:12px 32px;margin-top:24px;font-size:15px" onclick="location.reload()">Ek Aur Item Jodein</button>
    </div>

</div><!-- /wrapper -->


<!-- ═══════════════════════════════════════════
     CROP MODAL
═══════════════════════════════════════════ -->
<div id="cropModal">
    <div class="crop-inner">
        <div class="crop-header">
            <div>
                <p class="crop-header-text">✂️ Photo Crop Karo</p>
                <p class="crop-header-sub" id="cropSubtitle">Drag karke adjust karo</p>
            </div>
            <button class="crop-x" id="cropXBtn" onclick="closeCrop()">✕</button>
        </div>
        <div class="crop-canvas-wrap">
            <img id="cropImg" src="" alt="" />
        </div>
        <div class="crop-bar">
            <div class="ratio-row">
                <button class="ratio-btn on" onclick="setRatio(1/1,  this)">1 : 1</button>
                <button class="ratio-btn"    onclick="setRatio(4/3,  this)">4 : 3</button>
                <button class="ratio-btn"    onclick="setRatio(16/9, this)">16 : 9</button>
                <button class="ratio-btn"    onclick="setRatio(NaN,  this)">Free</button>
            </div>
            <div class="tool-row">
                <button class="tool-btn" onclick="cr('rotate',-90)">↺</button>
                <button class="tool-btn" onclick="cr('rotate', 90)">↻</button>
                <button class="tool-btn" onclick="cr('flipX')">⇔</button>
                <button class="tool-btn" onclick="cr('flipY')">⇕</button>
                <button class="tool-btn" onclick="cr('zoom', .1)">＋</button>
                <button class="tool-btn" onclick="cr('zoom',-.1)">－</button>
                <button class="tool-btn" onclick="cr('reset')">⟳</button>
            </div>
            <p class="webp-note">Output: <span class="webp-badge-g">WebP</span> &nbsp;• smaller file, better quality ✓</p>
            <div class="crop-action-row">
                <button class="crop-cancel" id="cropCancelBtn" onclick="closeCrop()">Cancel</button>
                <button class="crop-confirm" id="cropConfirmBtn" onclick="confirmCrop()">
                    <span class="btn-spinner"></span>
                    <span class="btn-label">✅ Crop & Save WebP</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>

/* ══════════════════════════════════════════════
   LOADER HELPERS
══════════════════════════════════════════════ */
function btnLoad(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.disabled = true;
    el.classList.add('loading');
}
function btnReset(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.disabled = false;
    el.classList.remove('loading');
}

/* ══════════════════════════════════════════════
   ORIGINAL CONFIG (unchanged)
══════════════════════════════════════════════ */
const shopTypes = {
    bakery: { emoji:'🎂', name:'Sweet Dreams Bakery',  cat:'Bakery & Cake',  categories:['Cakes','Pastry','Cookies'],          freeImages:['🎂','🧁','🍰'] },
    shoes:  { emoji:'👟', name:'Raj Footwear',          cat:'Shoes',          categories:['Sports','Formal','Casual'],           freeImages:['👟','👞','👠'] },
    cloth:  { emoji:'👗', name:'Jhansi Fashion',        cat:'Clothing',       categories:['Saree','Kurta','Jeans'],              freeImages:['👗','👔','🥻'] },
    salon:  { emoji:'✂️', name:'Glamour Salon & Spa',  cat:'Unisex Salon',   categories:['Haircut','Hair Color','Facial','Waxing','Manicure','Pedicure','Bridal','Massage'], freeImages:['✂️','💇','💆','💅','🧖','💄','🪮','✂️'] },
};

/* ══════════════════════════════════════════════
   STATE
══════════════════════════════════════════════ */
let currentItemId   = null;
let currentShopType = 'bakery';
let discountOn      = false;
let selectedLibImg  = null;

/* Crop state */
let cropper     = null;
let cropSlotIdx = null;   // 0 / 1 / 2
let flipXState  = 1;
let flipYState  = 1;

/* Step → button-id map */
const STEP_BTN = { basic:'btnBasicNext', photos:'btnPhotosNext', extras:'btnExtrasNext' };

/* WebP blobs & previews per slot */
const croppedBlobs    = [null, null, null];
const croppedPreviews = [null, null, null];

/* ══════════════════════════════════════════════
   INIT
══════════════════════════════════════════════ */
function init() {
    //changeShopType('bakery');
    buildPhotoGrid();
}

/* ══════════════════════════════════════════════
   SHOP TYPE  (original logic, unchanged)
══════════════════════════════════════════════ */
function changeShopType(type) {
    currentShopType = type;
    const cfg = shopTypes[type];
    
    document.getElementById('shopNameDisplay').textContent  = cfg.name;

    

    /* Salon div toggle */
    document.querySelectorAll('.salon_div').forEach(el => {
        el.style.display = (type === 'salon') ? 'block' : 'none';
    });

    /* Free image library */
    const libRow = document.getElementById('freeImageRow');
    libRow.innerHTML = '';
    cfg.freeImages.forEach(img => {
        const d = document.createElement('div');
        d.style.cssText = 'min-width:60px;height:60px;background:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:30px;cursor:pointer;border:2px solid transparent;flex-shrink:0;transition:border-color .15s';
        d.textContent = img;
        d.onclick = () => {
            document.querySelectorAll('#freeImageRow div').forEach(x => x.style.borderColor = 'transparent');
            d.style.borderColor = '#3B5BDB';
            selectedLibImg = img;
            document.getElementById('prevPhotoArea').textContent = img;
        };
        libRow.appendChild(d);
    });
}

/* ══════════════════════════════════════════════
   PHOTO GRID — 3 crop-enabled slots
   (replaces original buildPhotoGrid / previewFile)
══════════════════════════════════════════════ */
function buildPhotoGrid() {
    const grid = document.getElementById('photoGrid');
    grid.innerHTML = '';
    for (let i = 0; i < 3; i++) {
        const slot = document.createElement('div');
        slot.className = 'photo-slot';
        slot.id = `slot${i}`;
        slot.innerHTML = `
            <input type="file" id="rawFile${i}" accept="image/*" style="display:none"
                   onchange="openCrop(this,${i})">
            <div id="slotContent${i}"
                 style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"
                 onclick="document.getElementById('rawFile${i}').click()">
                <span class="slot-plus">＋</span>
            </div>`;
        grid.appendChild(slot);
    }
}

/* Refresh a slot's UI after a successful crop */
function refreshSlot(idx) {
    const slot    = document.getElementById(`slot${idx}`);
    const content = document.getElementById(`slotContent${idx}`);
    const preview = croppedPreviews[idx];

    slot.classList.add('filled');
    content.innerHTML = `
        <img src="${preview}" style="width:100%;height:100%;object-fit:cover;">
        <span class="webp-dot">WebP</span>
        <button class="slot-edit-btn"
                onclick="event.stopPropagation();document.getElementById('rawFile${idx}').click()"
                title="Re-crop">✏️</button>`;
    content.querySelector('img').onclick = () => document.getElementById(`rawFile${idx}`).click();

    /* Mirror first photo into preview tab */
    if (idx === 0) {
        document.getElementById('prevPhotoArea').innerHTML =
            `<img src="${preview}" style="width:100%;height:100%;object-fit:cover;">`;
    }
}

/* ══════════════════════════════════════════════
   CROP MODAL — OPEN
══════════════════════════════════════════════ */
function openCrop(inputEl, slotIdx) {
    const file = inputEl.files && inputEl.files[0];
    if (!file) return;

    cropSlotIdx = slotIdx;
    flipXState  = 1;
    flipYState  = 1;

    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('cropImg');
        img.src = e.target.result;

        document.getElementById('cropModal').classList.add('open');
        document.body.style.overflow = 'hidden';
        document.getElementById('cropSubtitle').textContent = `Photo ${slotIdx + 1} — Drag & resize to crop`;

        if (cropper) { cropper.destroy(); cropper = null; }
        setRatioBtnUI(1); // default 1:1 for item photos

        cropper = new Cropper(img, {
            aspectRatio: 1, viewMode: 1, dragMode: 'move',
            autoCropArea: 0.85, restore: false, guides: true,
            center: true, highlight: true,
            cropBoxMovable: true, cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    };
    reader.readAsDataURL(file);
    inputEl.value = ''; // allow re-select of same file
}

/* ══════════════════════════════════════════════
   CROP CONTROLS
══════════════════════════════════════════════ */
function setRatio(ratio, btn) {
    if (!cropper) return;
    cropper.setAspectRatio(isNaN(ratio) ? NaN : ratio);
    setRatioBtnUI(ratio);
}
function setRatioBtnUI(ratio) {
    document.querySelectorAll('.ratio-btn').forEach(b => b.classList.remove('on'));
    const idx = (ratio === 1) ? 0 : (ratio === 4/3) ? 1 : (ratio === 16/9) ? 2 : 3;
    const btns = document.querySelectorAll('.ratio-btn');
    if (btns[idx]) btns[idx].classList.add('on');
}
function cr(action, val) {
    if (!cropper) return;
    if (action === 'rotate') cropper.rotate(val);
    if (action === 'zoom')   cropper.zoom(val);
    if (action === 'reset')  { flipXState = 1; flipYState = 1; cropper.reset(); }
    if (action === 'flipX')  { flipXState *= -1; cropper.scaleX(flipXState); }
    if (action === 'flipY')  { flipYState *= -1; cropper.scaleY(flipYState); }
}

/* ══════════════════════════════════════════════
   CROP CONFIRM — canvas → WebP blob
   (loader on confirm btn; cancel & ✕ disabled)
══════════════════════════════════════════════ */
function confirmCrop() {
    if (!cropper) return;

    btnLoad('cropConfirmBtn');
    document.getElementById('cropCancelBtn').disabled = true;
    document.getElementById('cropXBtn').disabled      = true;

    const release = () => {
        btnReset('cropConfirmBtn');
        document.getElementById('cropCancelBtn').disabled = false;
        document.getElementById('cropXBtn').disabled      = false;
    };

    setTimeout(() => {
        const canvas = cropper.getCroppedCanvas({
            maxWidth: 1200, maxHeight: 1200,
            imageSmoothingEnabled: true, imageSmoothingQuality: 'high',
        });

        if (!canvas) { alert('Crop fail ho gaya. Dobara try karo.'); release(); return; }

        canvas.toBlob(blob => {
            if (!blob) { alert('WebP conversion fail!'); release(); return; }

            croppedBlobs[cropSlotIdx]    = blob;
            croppedPreviews[cropSlotIdx] = canvas.toDataURL('image/webp', 0.88);

            refreshSlot(cropSlotIdx);
            release();
            closeCrop();

            if (typeof Toast !== 'undefined') {
                Toast.fire({ icon:'success', title:`Photo ${cropSlotIdx + 1} — WebP save ho gaya! ✅` });
            }
        }, 'image/webp', 0.88);
    }, 30);
}

/* ══════════════════════════════════════════════
   CROP CLOSE
══════════════════════════════════════════════ */
function closeCrop() {
    document.getElementById('cropModal').classList.remove('open');
    document.body.style.overflow = '';
    if (cropper) { cropper.destroy(); cropper = null; }
}
document.getElementById('cropModal').addEventListener('click', function (e) {
    if (e.target === this) closeCrop();
});

/* ══════════════════════════════════════════════
   DISCOUNT TOGGLE  (original)
══════════════════════════════════════════════ */
function toggleDiscount() {
    discountOn = !discountOn;
    document.getElementById('discToggle').classList.toggle('on', discountOn);
    document.getElementById('discSection').style.display = discountOn ? 'block' : 'none';
}

/* ══════════════════════════════════════════════
   SAVE STEP  (original logic + loader + WebP)
══════════════════════════════════════════════ */
async function saveStep(current, next) {
    const btnId = STEP_BTN[current];
    btnLoad(btnId);   /* START loader */

    let formData = new FormData();
    if (currentItemId) formData.append('item_id', currentItemId);

    /* ── BASIC ── */
    if (current === 'basic') {
        const name = document.getElementById('itemName').value;
        const mrp  = document.getElementById('mrpPrice').value;
        if (!name || !mrp) {
            alert("Bhai, Naam aur Price toh daal de!");
            btnReset(btnId); return;
        }
        formData.append('item_name',        name);
        formData.append('mrp_price',        mrp);
        formData.append('discount_price',   document.getElementById('discPrice').value);
        formData.append('category',         document.querySelector('.chip.on')?.textContent || '');
        formData.append('salon_cat',        document.getElementById('selected_cat').value);
        formData.append('service_duration', document.getElementById('service_duration').value);
    }

    /* ── PHOTOS — send WebP blobs (replaces original photos[] logic) ── */
    if (current === 'photos') {
        const hasAny = croppedBlobs.some(b => b !== null);
        if (!hasAny) {
            alert('Kam se kam ek photo crop karke add karo!');
            btnReset(btnId); return;
        }
        croppedBlobs.forEach((blob, i) => {
            if (blob) formData.append(`photo_${i + 1}`, blob, `item_photo_${i + 1}.webp`);
        });
    }

    /* ── EXTRAS ── */
    if (current === 'extras') {
        formData.append('tags', document.getElementById('tags').value);
    }

    /* ── AJAX ── */
    try {
        const response = await fetch("{{ url('item/save-step') }}", {
            method : 'POST',
            body   : formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const res = await response.json();
        if (res.success) {
            currentItemId = res.item_id;
            btnReset(btnId);
            switchTab(document.getElementById('btn-' + next), next);
        } else {
            alert(res.message || 'Save fail ho gaya!');
            btnReset(btnId);
        }
    } catch (e) {
        alert("Server error!");
        btnReset(btnId);
    }
}

/* ══════════════════════════════════════════════
   SWITCH TAB  (original, unchanged)
══════════════════════════════════════════════ */
function switchTab(btn, name) {
    document.querySelectorAll('[id^="tab-"]').forEach(s => s.style.display = 'none');
    document.getElementById('tab-' + name).style.display = 'block';
    document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
    if (btn) btn.classList.add('active');

    if (name === 'preview') {
        const mrp      = document.getElementById('mrpPrice').value;
        const disc     = document.getElementById('discPrice').value;
        const vPrice   = document.getElementById('vPrice');
        const vMrp     = document.getElementById('vMrp');
        const vBadge   = document.getElementById('vBadge');
    
        document.getElementById('vName').textContent = document.getElementById('itemName').value;
    
        if (discountOn && disc && parseFloat(disc) < parseFloat(mrp)) {
            const pct = Math.round((1 - disc / mrp) * 100);
            vPrice.textContent        = "₹ " + disc;
            vMrp.textContent          = "₹ " + mrp;
            vMrp.style.display        = 'block';
            vBadge.textContent        = pct + "% OFF";
            vBadge.style.display      = 'inline-block';
        } else {
            vPrice.textContent        = "₹ " + mrp;
            vMrp.style.display        = 'none';
            vBadge.style.display      = 'none';
        }
    }
    window.scrollTo(0, 0);
}

/* ══════════════════════════════════════════════
   GO LIVE  (loader added)
══════════════════════════════════════════════ */
function finalizeLive() {
    btnLoad('btnGoLive');
    setTimeout(() => {
        btnReset('btnGoLive');
        document.getElementById('successOverlay').style.display = 'flex';
    }, 800);
}

/* ══════════════════════════════════════════════
   SALON FOR-WHOM  (original, unchanged)
══════════════════════════════════════════════ */
function setStock(element, val) {
    document.querySelectorAll('.chp').forEach(b => b.classList.remove('on'));
    element.classList.add('on');
    document.getElementById('selected_cat').value = val;
}

$(document).ready(function() {
    // #catChips ke andar kisi bhi .chip par click ho
    $('#catChips').on('click', '.chip', function() {
        
        // 1. Pehle saari chips se 'on' class hata do
        $('#catChips .chip').removeClass('on');
        
        // 2. Phir sirf us chip par 'on' class lagao jis par click hua hai
        $(this).addClass('on');
        
    });
});

init();
</script>
@endpush