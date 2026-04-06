@extends('front_layout.main')
@push('css_or_link')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif
        }

        .fd {
            font-family: 'Baloo 2', sans-serif
        }

        body {
            background: #f0f4ff;
            min-height: 100vh
        }

        /* ── Input base ── */
        .inp {
            width: 100%;
            background: white;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            padding: 13px 16px;
            font-size: 14px;
            color: #1e293b;
            font-family: 'Nunito', sans-serif;
            outline: none;
            transition: all .2s
        }

        .inp:focus {
            border-color: #3595FF;
            box-shadow: 0 0 0 3px rgba(53, 149, 255, .12)
        }

        .inp::placeholder {
            color: #94a3b8
        }

        .inp.error {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .1)
        }

        .inp.ok {
            border-color: #22C55E;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, .1)
        }

        select.inp {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px
        }

        textarea.inp {
            resize: none;
            height: 80px
        }

        /* ── Label ── */
        .lbl {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px
        }

        .req {
            color: #EF4444;
            font-size: 14px;
            line-height: 1
        }

        /* ── Step pill ── */
        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            transition: all .3s;
            flex-shrink: 0
        }

        .step-line {
            flex: 1;
            height: 2px;
            transition: all .3s
        }

        /* ── Photo upload ── */
        .photo-box {
            border: 2px dashed #CBD5E1;
            border-radius: 16px;
            background: white;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            overflow: hidden
        }

        .photo-box:hover {
            border-color: #3595FF;
            background: #EFF6FF
        }

        .photo-box.has-img {
            border-style: solid;
            border-color: #22C55E
        }

        .photo-box input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%
        }

        /* ── Category chip ── */
        .cat-chip {
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
            background: white;
            color: #475569;
            white-space: nowrap;
            user-select: none
        }

        .cat-chip.sel {
            background: linear-gradient(135deg, #417dbf, #3595ff);
            color: white;
            border-color: #3595ff
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fu {
            animation: fadeUp .35s ease both
        }

        @keyframes pop {
            0% {
                transform: scale(.5);
                opacity: 0
            }

            70% {
                transform: scale(1.08)
            }

            100% {
                transform: scale(1);
                opacity: 1
            }
        }

        .pop {
            animation: pop .4s cubic-bezier(.34, 1.56, .64, 1) both
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        .sl {
            animation: slideIn .3s ease both
        }

        @keyframes confetti {
            0% {
                transform: translateY(0) rotate(0);
                opacity: 1
            }

            100% {
                transform: translateY(-60px) rotate(360deg);
                opacity: 0
            }
        }

        /* ── Button ── */
        .btn-main {
            width: 100%;
            background: linear-gradient(135deg, #417dbf, #3595ff);
            color: white;
            font-family: 'Baloo 2', sans-serif;
            font-size: 17px;
            font-weight: 700;
            border: none;
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all .15s;
            letter-spacing: .3px
        }

        .btn-main:hover {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(53, 149, 255, .35)
        }

        .btn-main:active {
            transform: scale(.97)
        }

        .btn-main:disabled {
            opacity: .5;
            cursor: default;
            transform: none
        }

        .btn-sec {
            background: white;
            color: #3595ff;
            border: 1.5px solid #3595ff;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            font-weight: 700;
            border-radius: 14px;
            padding: 12px;
            cursor: pointer;
            transition: all .15s
        }

        .btn-sec:hover {
            background: #EFF6FF
        }

        /* ── Success screen ── */
        .confetti-piece {
            position: absolute;
            border-radius: 2px;
            pointer-events: none
        }
    </style>
@endpush
@section('content')
    <div style="max-width:480px;margin:0 auto;min-height:100vh;display:flex;flex-direction:column">

        <!-- ── TOP BAR ── -->
        <div style="background:linear-gradient(135deg,#417dbf,#3595ff);padding:16px 20px;position:sticky;top:0;z-index:50">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div>
                    <p class="fd" style="color:white;font-size:18px;font-weight:800;line-height:1">Jhansi Bazaar</p>
                    <p style="color:rgba(255,255,255,.7);font-size:11px;margin-top:1px">Apni Dukan Register Karo — Free!</p>
                </div>
                <div style="background:rgba(255,255,255,.2);border-radius:20px;padding:5px 12px">
                    <p style="color:white;font-size:11px;font-weight:700">Step <span id="stepLabel">1</span> / 3</p>
                </div>
            </div>

            <!-- Progress bar -->
            <div style="background:rgba(255,255,255,.2);border-radius:10px;height:6px;margin-top:12px;overflow:hidden">
                <div id="progressBar"
                    style="height:100%;background:white;border-radius:10px;width:33%;transition:width .4s ease"></div>
            </div>

            <!-- Step indicators -->
            <div style="display:flex;align-items:center;margin-top:10px;gap:0">
                <div style="display:flex;align-items:center;gap:6px">
                    <div id="dot1" class="step-dot" style="background:white;color:#3595ff;font-size:12px">1</div>
                    <p id="lbl1" style="color:white;font-size:11px;font-weight:700">Basic Info</p>
                </div>
                <div id="line1" class="step-line" style="background:rgba(255,255,255,.3);margin:0 8px"></div>
                <div style="display:flex;align-items:center;gap:6px">
                    <div id="dot2" class="step-dot"
                        style="background:rgba(255,255,255,.3);color:rgba(255,255,255,.7);font-size:12px">2</div>
                    <p id="lbl2" style="color:rgba(255,255,255,.5);font-size:11px;font-weight:700">Category</p>
                </div>
                <div id="line2" class="step-line" style="background:rgba(255,255,255,.3);margin:0 8px"></div>
                <div style="display:flex;align-items:center;gap:6px">
                    <div id="dot3" class="step-dot"
                        style="background:rgba(255,255,255,.3);color:rgba(255,255,255,.7);font-size:12px">3</div>
                    <p id="lbl3" style="color:rgba(255,255,255,.5);font-size:11px;font-weight:700">Photos</p>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- STEP 1 — BASIC INFO -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div id="step1" class="fu" style="flex:1;padding:20px 16px 100px">

            <div
                style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border-radius:16px;padding:14px;margin-bottom:20px;border:1px solid #BFDBFE">
                <p class="fd" style="font-size:16px;font-weight:700;color:#1e40af;margin-bottom:3px">👋 Swagat Hai
                    Aapka!</p>
                <p style="font-size:12px;color:#3b82f6;line-height:1.5">Sirf 3 steps mein apni dukan register karo. 5 minute
                    mein profile ready ho jaayega!</p>
            </div>

            <!-- Owner Name -->
            <div style="margin-bottom:16px">
                <p class="lbl">👤 Aapka Naam <span class="req">*</span></p>
                <input id="ownerName" class="inp" type="text" placeholder="Jaise: Ramesh Sharma"
                    oninput="validateName(this)" />
                <p id="ownerNameErr" style="display:none;font-size:11px;color:#EF4444;margin-top:4px;font-weight:600">⚠ Naam
                    daalna zaroori hai</p>
            </div>

            <!-- Shop Name -->
            <div style="margin-bottom:16px">
                <p class="lbl">🏪 Dukan Ka Naam <span class="req">*</span></p>
                <input id="shopName" class="inp" type="text" placeholder="Jaise: Sharma General Store"
                    oninput="validateShop(this)" />
                <p id="shopNameErr" style="display:none;font-size:11px;color:#EF4444;margin-top:4px;font-weight:600">⚠ Dukan
                    ka
                    naam zaroori hai</p>
            </div>

            <!-- Phone / WhatsApp -->
            <div style="margin-bottom:16px">
                <p class="lbl">📱 Mobile / WhatsApp Number <span class="req">*</span></p>
                <div style="position:relative">
                    <div
                        style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#64748b;font-size:14px;font-weight:700;pointer-events:none">
                        +91</div>
                    <input id="phone" class="inp" type="tel" style="padding-left:48px" maxlength="10"
                        placeholder="98765 43210" oninput="validatePhone(this)" inputmode="numeric" />
                </div>
                <p id="phoneErr" style="display:none;font-size:11px;color:#EF4444;margin-top:4px;font-weight:600">⚠ 10
                    digit ka
                    number daalo</p>
                <div style="display:flex;align-items:center;gap:6px;margin-top:6px">
                    <input type="checkbox" id="waCheck" style="width:16px;height:16px;accent-color:#25D366" checked />
                    <label for="waCheck" style="font-size:12px;color:#64748b;font-weight:600">Yahi number WhatsApp pe bhi
                        hai</label>
                </div>
            </div>

            <!-- Address -->
            <div style="margin-bottom:16px">
                <p class="lbl">📍 Dukan Ka Pata <span class="req">*</span></p>
                <textarea id="address" class="inp" placeholder="Jaise: Shop No. 14, Sadar Bazar, Jhansi — 284001"
                    oninput="validateAddress(this)"></textarea>
                <p id="addressErr" style="display:none;font-size:11px;color:#EF4444;margin-top:4px;font-weight:600">⚠ Pata
                    daalna zaroori hai</p>
            </div>

            <!-- Timing -->
            <div style="margin-bottom:20px">
                <p class="lbl">🕐 Dukan Kab Khuli Rehti Hai</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin-bottom:4px;font-weight:600">Khulne Ka Waqt</p>
                        <select class="inp" id="openTime">
                            <option>5:00 AM</option>
                            <option>6:00 AM</option>
                            <option>7:00 AM</option>
                            <option selected>8:00 AM</option>
                            <option>9:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>12:00 PM</option>
                        </select>
                    </div>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin-bottom:4px;font-weight:600">Bandh Hone Ka Waqt</p>
                        <select class="inp" id="closeTime">
                            <option>6:00 PM</option>
                            <option>7:00 PM</option>
                            <option>8:00 PM</option>
                            <option selected>9:00 PM</option>
                            <option>10:00 PM</option>
                            <option>11:00 PM</option>
                            <option>12:00 AM</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top:8px">
                    <p style="font-size:11px;color:#94a3b8;margin-bottom:6px;font-weight:600">Chhuti Ka Din (Optional)</p>
                    <div style="display:flex;gap:6px;flex-wrap:wrap" id="offDays">
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Sun">Sun</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Mon">Mon</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Tue">Tue</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Wed">Wed</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Thu">Thu</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Fri">Fri</button>
                        <button type="button" class="cat-chip" onclick="toggleDay(this)" data-day="Sat">Sat</button>
                    </div>
                </div>
            </div>

            <button class="btn-main" onclick="saveStep1AndNext()">Aage Badho — Category Chuno →</button>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- STEP 2 — CATEGORY + SERVICE NAME + DESCRIPTION -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div id="step2" style="display:none;flex:1;padding:20px 16px 100px">

            <!-- Category select -->
            <div style="margin-bottom:20px">
                <p class="lbl">🏷️ Apni Category Chuno <span class="req">*</span></p>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:10px">Ek ya zyada select kar sakte ho</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap" id="catGrid">
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="food">🍛 Food &
                        Dhaba</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="sweets">🍡 Sweets &
                        Mithai</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="bakery">🎂 Bakery &
                        Cake</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="salon">✂️ Salon &
                        Spa</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="kirana">🛒 Kirana &
                        Grocery</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="clothing">👗 Kapde &
                        Fashion</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="footwear">👟 Joote &
                        Footwear</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="medical">💊 Medical &
                        Pharmacy</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="hospital">🏥 Hospital &
                        Clinic</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="hardware">🔧 Hardware &
                        Tools</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="paan">🌿 Paan &
                        General</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="electronics">📱
                        Electronics &
                        Mobile</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="coaching">📚 Coaching &
                        Classes</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="dairy">🥛 Dairy &
                        Milk</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="taxi">🚖 Taxi &
                        Transport</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="other">➕ Kuch
                        Aur</button>
                </div>
                <p id="catErr" style="display:none;font-size:11px;color:#EF4444;margin-top:8px;font-weight:600">⚠ Kam
                    se kam ek
                    category zaroori hai</p>
            </div>

            <!-- Service name / tagline -->
            <div style="margin-bottom:16px">
                <p class="lbl">✍️ Aapki Dukan Ki Khaasiyat <span class="req">*</span></p>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px">Ek line mein batao — customer kyun aaye?</p>
                <input id="tagline" class="inp" type="text" maxlength="60"
                    placeholder="Jaise: Best Butter Chicken in Jhansi!" oninput="updateCharCount(this,'tagCount',60)" />
                <p id="tagCount" style="text-align:right;font-size:11px;color:#94a3b8;margin-top:3px">0/60</p>
            </div>

            <!-- Description -->
            <div style="margin-bottom:16px">
                <p class="lbl">📝 Dukan Ke Baare Mein (Optional)</p>
                <textarea id="desc" class="inp" style="height:90px" maxlength="200"
                    placeholder="Jaise: Hum 1998 se Jhansi mein hain. Fresh food, clean kitchen, affordable prices. Roz subah 8 baje khulte hain..."
                    oninput="updateCharCount(this,'descCount',200)"></textarea>
                <p id="descCount" style="text-align:right;font-size:11px;color:#94a3b8;margin-top:3px">0/200</p>
            </div>

            <!-- Payment modes -->
            <div style="margin-bottom:20px">
                <p class="lbl">💳 Payment Modes</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <button type="button" class="cat-chip sel" onclick="toggleCat(this)" data-cat="cash">💵
                        Cash</button>
                    <button type="button" class="cat-chip sel" onclick="toggleCat(this)" data-cat="upi">📲 UPI /
                        GPay</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="card">💳 Card</button>
                    <button type="button" class="cat-chip" onclick="toggleCat(this)" data-cat="credit">📋
                        Udhaar</button>
                </div>
            </div>

            <!-- Offers preview -->
            <div style="background:#EFF6FF;border:1.5px solid #BFDBFE;border-radius:16px;padding:14px;margin-bottom:20px">
                <p class="fd" style="color:#1e40af;font-size:14px;font-weight:700;margin-bottom:8px">🎡 Aapke Spin
                    Wheel Offers
                </p>
                <p style="font-size:12px;color:#3b82f6;margin-bottom:10px">3 offers set karo — customer spin karke jeete
                    (baad
                    mein edit bhi kar sakte ho)</p>
                <div style="display:flex;flex-direction:column;gap:8px">
                    <input id="offer1" class="inp" type="text"
                        placeholder="Offer 1 — Jaise: 10% off on ₹100 purchase" style="font-size:13px" />
                    <input id="offer2" class="inp" type="text"
                        placeholder="Offer 2 — Jaise: Free item on ₹50 order" style="font-size:13px" />
                    <input id="offer3" class="inp" type="text" placeholder="Offer 3 — Jaise: Bonus 20 coins"
                        style="font-size:13px" />
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 2fr;gap:10px">
                <button class="btn-sec" onclick="goStep(1)">← Wapas</button>
                <button class="btn-main" onclick="saveStep2AndNext()">Aage — Photos Daalo →</button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- STEP 3 — PHOTOS -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div id="step3" style="display:none;flex:1;padding:20px 16px 100px">

            <div style="background:#ECFDF5;border:1.5px solid #86EFAC;border-radius:14px;padding:12px;margin-bottom:18px">
                <p style="font-size:12px;color:#16A34A;font-weight:700;line-height:1.5">📸 Photos se profile attractive
                    banta
                    hai — zyada customers aate hain. Mobile se seedha click karke upload karo!</p>
            </div>

            <!-- Shop Photo — Main -->
            <div style="margin-bottom:16px">
                <p class="lbl">🏪 Dukan Ki Photo <span class="req">*</span></p>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px">Dukan ke bahar ya andar ki clear photo</p>
                <div class="photo-box" id="shopPhotoBox"
                    style="height:140px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:6px"
                    onclick="triggerUpload('shopPhoto')">
                    <input type="file" id="shopPhoto" accept="image/*" style="display:none"
                        onchange="previewPhoto(this,'shopPhotoBox','shopPreview')" />
                    <div id="shopPreview"
                        style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:6px">
                        <div
                            style="width:48px;height:48px;background:#EFF6FF;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px">
                            📷</div>
                        <p style="font-size:13px;font-weight:700;color:#64748b">Photo Click Karo ya Upload Karo</p>
                        <p style="font-size:11px;color:#94a3b8">JPG ya PNG • Max 5MB</p>
                    </div>
                </div>
                <p id="shopPhotoErr" style="display:none;font-size:11px;color:#EF4444;margin-top:4px;font-weight:600">⚠
                    Dukan ki
                    ek photo zaroori hai</p>
            </div>

            <!-- Owner Photo -->
            <div style="margin-bottom:16px">
                <p class="lbl">👤 Aapki Photo (Optional)</p>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px">Customer aapko pehchaane — trust badhta hai</p>
                <div class="photo-box" id="ownerPhotoBox"
                    style="height:110px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:4px"
                    onclick="triggerUpload('ownerPhoto')">
                    <input type="file" id="ownerPhoto" accept="image/*" style="display:none"
                        onchange="previewPhoto(this,'ownerPhotoBox','ownerPreview')" />
                    <div id="ownerPreview"
                        style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:4px">
                        <div
                            style="width:42px;height:42px;background:#F1F5F9;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px">
                            🤳</div>
                        <p style="font-size:12px;font-weight:700;color:#64748b">Selfie ya Photo Upload</p>
                    </div>
                </div>
            </div>

            <!-- Item / Service Photos -->
            <div style="margin-bottom:16px">
                <p class="lbl">🍽️ Items / Services Ki Photos</p>
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px">Jo bhi bechte ho — uski photos daalo (max 6)</p>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px" id="itemPhotosGrid">
                    <!-- Generated by JS -->
                </div>
            </div>

            <!-- Video option -->
            <div style="background:#F8FAFC;border:1.5px solid #E2E8F0;border-radius:14px;padding:12px;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:10px">
                    <div
                        style="width:40px;height:40px;background:linear-gradient(135deg,#417dbf,#3595ff);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
                        🎬</div>
                    <div style="flex:1">
                        <p style="font-size:13px;font-weight:700;color:#1e293b">30 Second Video (Recommended)</p>
                        <p style="font-size:11px;color:#64748b">Humari team aapki dukan ki video FREE banayegi</p>
                    </div>
                    <div>
                        <input type="checkbox" id="videoReq" style="width:20px;height:20px;accent-color:#3595ff"
                            checked />
                    </div>
                </div>
                <div id="videoNote" style="margin-top:10px;background:#EFF6FF;border-radius:10px;padding:8px 10px">
                    <p style="font-size:11px;color:#3595ff;font-weight:600">✓ Hamare team member aapke paas aayenge aur
                        video
                        banayenge — 2-3 din mein</p>
                </div>
            </div>

            <!-- Profile Preview Card -->
            <div style="background:white;border-radius:16px;padding:14px;border:1.5px solid #E2E8F0;margin-bottom:20px"
                id="previewCard">
                <p
                    style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px">
                    Aapka Profile Preview</p>
                <div style="display:flex;gap:12px;align-items:center">
                    <div id="previewAvatar"
                        style="width:52px;height:52px;background:linear-gradient(135deg,#417dbf,#3595ff);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0">
                        🏪</div>
                    <div style="flex:1;min-width:0">
                        <p id="previewShopName" class="fd"
                            style="font-size:15px;font-weight:800;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            Aapki Dukan</p>
                        <p id="previewCategory" style="font-size:11px;color:#64748b;margin-top:1px">Category • Jhansi</p>
                        <p id="previewTagline" style="font-size:11px;color:#3595ff;font-weight:600;margin-top:2px">Aapki
                            tagline
                            yahan aayegi</p>
                    </div>
                </div>
                <div style="display:flex;gap:6px;margin-top:10px">
                    <span
                        style="background:#EFF6FF;color:#3595ff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #BFDBFE">★
                        New Shop</span>
                    <span
                        style="background:#ECFDF5;color:#16A34A;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #86EFAC">🟢
                        Active</span>
                    <span
                        style="background:#FEF9C3;color:#854d0e;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #FDE68A">🎡
                        Spin Live</span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 2fr;gap:10px">
                <button class="btn-sec" onclick="goStep(2)">← Wapas</button>
                <button class="btn-main" onclick="submitForm()">🚀 Profile Banao — Free!</button>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- SUCCESS SCREEN -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div id="stepSuccess"
            style="display:none;flex:1;padding:24px 16px;text-align:center;position:relative;overflow:hidden"
            class="fu">

            <div id="confettiContainer" style="position:absolute;inset:0;pointer-events:none"></div>

            <div style="margin-top:0px;margin-bottom:28px">
                <div style="width: 37px;/* height: 96px; */background:linear-gradient(135deg,#417dbf,#3595ff);border-radius: 8px;display:flex;align-items:center;justify-content:center;font-size: 25px;margin: 0 auto 0px;box-shadow:0 12px 32px rgba(53,149,255,.35);/* display: none; */"
                    class="pop hidden">✅</div>
                <h2 class="fd" style="font-size:26px;font-weight:800;color:#1e293b;margin-bottom:6px">Badhai Ho!</h2>
                <p style="font-size:15px;color:#64748b;line-height:1.5">Aapki dukan register ho gayi.<br />Profile 24
                    ghante
                    mein live ho jaayega!</p>
            </div>

            <!-- What happens next -->
            <div
                style="background:white;border-radius:20px;padding:18px;border:1.5px solid #E2E8F0;margin-bottom:16px;text-align:left">
                <p class="fd" style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:14px">Aage Kya Hoga:
                </p>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="display:flex;gap:12px;align-items:flex-start">
                        <div
                            style="width:32px;height:32px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">
                            1</div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1e293b">QR Code milega</p>
                            <p style="font-size:11px;color:#64748b">WhatsApp pe aapka unique QR aur link bheja jaayega</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:12px;align-items:flex-start">
                        <div
                            style="width:32px;height:32px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">
                            2</div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1e293b">Video team aayegi</p>
                            <p style="font-size:11px;color:#64748b">2-3 din mein humari team aapki dukan ki video banayegi
                            </p>
                        </div>
                    </div>
                    <div style="display:flex;gap:12px;align-items:flex-start">
                        <div
                            style="width:32px;height:32px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">
                            3</div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#1e293b">Profile live!</p>
                            <p style="font-size:11px;color:#64748b">Customers aapki dukan dhundhenge aur spin karenge</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration ID -->
            <div
                style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border-radius:14px;padding:14px;margin-bottom:20px;border:1.5px solid #BFDBFE">
                <p style="font-size:11px;color:#64748b;margin-bottom:4px">Aapka Registration ID</p>
                <p id="regId" class="fd" style="font-size:24px;font-weight:800;color:#1e40af;letter-spacing:2px">
                    JB-2025-0041
                </p>
                <p style="font-size:11px;color:#3b82f6;margin-top:4px">Yeh ID save kar lo — kaam aayega</p>
            </div>

            <button class="btn-main" onclick="shareWhatsApp()" style="margin-bottom:10px">
                💬 WhatsApp Pe Share Karo
            </button>
            <button onclick="resetForm()"
                style="width:100%;background:transparent;border:1.5px solid #E2E8F0;border-radius:14px;padding:13px;font-size:14px;font-weight:700;color:#64748b;cursor:pointer;font-family:'Nunito',sans-serif">
                Ek Aur Dukan Register Karo
            </button>

        </div>

    </div>
@endsection
@push('script')
    <script>
        // ─── 1. GLOBAL VARIABLES ───
        let currentStep = 1;
        let shopId = null; // Step 1 save hone ke baad DB se aayegi
        let selectedCategories = [];
        let selectedOffDays = [];

        $(document).ready(function() {
            // Initial Grid Setup
            renderItemPhotoGrid();

            // Video note toggle logic
            $('#videoReq').on('change', function() {
                $('#videoNote').toggle(this.checked);
            });
        });

        // ─── 2. VALIDATION FUNCTIONS (Solving your Console Errors) ───
        function validateName(el) {
            if (el.value.trim().length > 0) {
                $('#ownerNameErr').fadeOut();
                $(el).css('border-color', '#3595ff');
            } else {
                $(el).css('border-color', '#EF4444');
            }
        }

        function validateShop(el) {
            if (el.value.trim().length > 0) {
                $('#shopNameErr').fadeOut();
                $(el).css('border-color', '#3595ff');
                $('#previewShopName').text(el.value || 'Aapki Dukan');
            } else {
                $(el).css('border-color', '#EF4444');
            }
        }

        function validatePhone(el) {
            let val = el.value.replace(/\D/g, '').substring(0, 10);
            el.value = val;
            if (val.length === 10) {
                $('#phoneErr').fadeOut();
                $(el).css('border-color', '#3595ff');
            } else {
                $(el).css('border-color', '#EF4444');
            }
        }

        function validateAddress(el) {
            if (el.value.trim().length > 5) {
                $('#addressErr').fadeOut();
                $(el).css('border-color', '#3595ff');
            } else {
                $(el).css('border-color', '#EF4444');
            }
        }

        function updateCharCount(el, countId, max) {
            let len = el.value.length;
            $(`#${countId}`).text(len + '/' + max);
            if (el.id === 'tagline') {
                $('#previewTagline').text(el.value || 'Aapki tagline yahan aayegi');
            }
            if (len > max) $(el).css('border-color', 'red');
            else $(el).css('border-color', '#3595ff');
        }

        // ─── 3. PHOTO PREVIEW LOGIC ───
        function triggerUpload(id) {
            document.getElementById(id).click();
        }

        function previewPhoto(input, boxId, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#${previewId}`).html(
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">`
                        );
                    $(`#${boxId}`).css('border-style', 'solid').css('border-color', '#3595ff');
                    if (previewId === 'shopPreview') {
                        $('#previewAvatar').html(
                            `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">`
                            );
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function renderItemPhotoGrid() {
            let html = '';
            for (let i = 1; i <= 6; i++) {
                html += `
                <div class="photo-box" id="itemBox${i}" style="height:85px;border:2px dashed #cbd5e1;border-radius:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;overflow:hidden" onclick="triggerUpload('itemPhoto${i}')">
                    <input type="file" id="itemPhoto${i}" accept="image/*" style="display:none" onchange="previewItemPhoto(this, ${i})">
                    <div id="itemPreview${i}">➕</div>
                </div>`;
            }
            $('#itemPhotosGrid').html(html);
        }

        function previewItemPhoto(input, index) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    $(`#itemPreview${index}`).html(
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`);
                    $(`#itemBox${index}`).css('border-style', 'solid').css('border-color', '#3595ff');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ─── 4. STEP NAVIGATION & DB SYNC ───
        function goStep(step) {
            $('.fu, #step1, #step2, #step3').hide();
            $(`#step${step}`).fadeIn();
            currentStep = step;
            updateUI(step);
            window.scrollTo(0, 0);
        }

        function updateUI(step) {
            $('#progressBar').css('width', (step / 3) * 100 + '%');
            $('#stepLabel').text(step);
            for (let i = 1; i <= 3; i++) {
                if (i <= step) {
                    $(`#dot${i}`).css({
                        'background': 'white',
                        'color': '#3595ff'
                    });
                    $(`#lbl${i}`).css('color', 'white');
                } else {
                    $(`#dot${i}`).css({
                        'background': 'rgba(255,255,255,.3)',
                        'color': 'rgba(255,255,255,.7)'
                    });
                    $(`#lbl${i}`).css('color', 'rgba(255,255,255,.5)');
                }
            }
        }

        // Save Step 1 (AJAX)
        async function saveStep1AndNext() {
            if (!$('#ownerName').val() || !$('#shopName').val() || $('#phone').val().length < 10 || !$('#address')
            .val()) {
                validateStep1();
                return;
            }
            const isWhatsapp = $('#is_whatsapp').is(':checked') ? 1 : 0;
            const data = {
                _token: '{{ csrf_token() }}',
                shop_id: shopId,
                owner_name: $('#ownerName').val(),
                shop_name: $('#shopName').val(),
                phone: $('#phone').val(),
                is_whatsapp: isWhatsapp,
                address: $('#address').val(),
                open_time: $('#openTime').val(),
                close_time: $('#closeTime').val(),
                off_days: JSON.stringify(selectedOffDays)
            };

            try {
                const res = await $.post("{{ url('/save-step1') }}", data);
                if (res.success) {
                    shopId = res.shop_id;
                    Toast.fire({
                        icon: 'success',
                        title: 'Step 1 Saved! Category chuno.'
                    });
                    goStep(2);
                } else {
                    // alert("Error: " + res.message);
                    Toast.fire({
                        icon: 'error',
                        title: res.message
                    });
                }
            } catch (e) {
                alert("Server Error! Check Route or Controller.");
            }
        }

        // Save Step 2 (AJAX)
        async function saveStep2AndNext() {
            if (selectedCategories.length === 0 || !$('#tagline').val()) {

                Toast.fire({
                    icon: 'error',
                    title: "Category aur Tagline zaroori hai!"
                });
                return;
            }

            const data = {
                _token: '{{ csrf_token() }}',
                shop_id: shopId,
                categories: JSON.stringify(selectedCategories),
                tagline: $('#tagline').val(),
                description: $('#desc').val(),
                offers: JSON.stringify([$('#offer1').val(), $('#offer2').val(), $('#offer3').val()])
            };

            try {
                const res = await $.post("{{ url('/save-step2') }}", data);
                if (res.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Step 2 Saved! '
                    });
                    goStep(3);
                } else {
                    // alert("Error: " + res.message);
                    Toast.fire({
                        icon: 'error',
                        title: res.message
                    });
                }
            } catch (e) {
                alert("Data save nahi ho paya!");
            }
        }

        // Final Submit (Images + DB Update)
        function submitForm() {
            if (!$('#shopPhoto')[0].files[0]) {

                Toast.fire({
                    icon: 'error',
                    title: "Dukan ki photo daalna zaroori hai!"
                });
                return;
            }

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('shop_id', shopId);
            formData.append('shop_photo', $('#shopPhoto')[0].files[0]);
            if ($('#ownerPhoto')[0].files[0]) formData.append('owner_photo', $('#ownerPhoto')[0].files[0]);

            for (let i = 1; i <= 6; i++) {
                let file = $(`#itemPhoto${i}`)[0].files[0];
                if (file) formData.append(`item_photo_${i}`, file);
            }

            $.ajax({
                url: "{{ url('/final-submit') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.success) {
                        $('#step3').hide();
                        $('#stepSuccess').fadeIn();
                        $('#regId').text(res.reg_id || 'JB-' + shopId);
                    }
                },
                error: function() {
                    alert("Image upload fail ho gayi!");
                }
            });
        }

        // ─── 5. UI TOGGLES ───
        function toggleCat(btn) {
            let cat = $(btn).data('cat');
            $(btn).toggleClass('sel');
            if ($(btn).hasClass('sel')) {
                selectedCategories.push(cat);
            } else {
                selectedCategories = selectedCategories.filter(c => c !== cat);
            }
            $('#previewCategory').text(selectedCategories.join(', ') || 'Category • Jhansi');
        }

        function toggleDay(btn) {
            $(btn).toggleClass('sel');
            let day = $(btn).data('day');
            if ($(btn).hasClass('sel')) selectedOffDays.push(day);
            else selectedOffDays = selectedOffDays.filter(d => d !== day);
        }

        function validateStep1() {
            let ok = true;
            if (!document.getElementById('ownerName').value.trim()) {
                showErr('ownerNameErr');
                markErr('ownerName');
                ok = false;
            }
            if (!document.getElementById('shopName').value.trim()) {
                showErr('shopNameErr');
                markErr('shopName');
                ok = false;
            }
            const ph = document.getElementById('phone').value.replace(/\s/g, '');
            if (ph.length !== 10 || isNaN(ph)) {
                showErr('phoneErr');
                markErr('phone');
                ok = false;
            }
            if (!document.getElementById('address').value.trim()) {
                showErr('addressErr');
                markErr('address');
                ok = false;
            }
            return ok;
        }

        function validateStep2() {
            if (selectedCats.size === 0) {
                showErr('catErr');
                return false;
            }
            if (!document.getElementById('tagline').value.trim()) {
                markErr('tagline');
                return false;
            }
            return true;
        }

        function showErr(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = 'block';
            setTimeout(() => {
                if (el) el.style.display = 'none';
            }, 3000);
        }

        function markErr(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('error');
                setTimeout(() => el.classList.remove('error'), 2000);
            }
        }
    </script>
@endpush
