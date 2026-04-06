@extends('front_layout.main')
@section('content')
  <div class="container mt-3 mb-5">
    <div class="card p-4 shadow-sm mb-5">
        <h4 class="mb-4 text-center">रजिस्टर करें</h4>
        <a href="{{url('/register_step')}}" class="btn btn-sm btn-success">Register Step</a>
        <form>
            <div class="mb-1">
                <label class="form-label">नाम</label>
                <input type="text" class="form-control" placeholder="अपना पूरा नाम डालें">
            </div>

            <div class="mb-1">
                <label class="form-label">श्रेणी चुनें</label>
                <select class="form-select">
                    <option selected disabled>-- चयन करें --</option>
                    <option value="1">ऑटो ड्राइवर</option>
                    <option value="2">कार / टैक्सी ड्राइवर</option>
                </select>
            </div>

            <div class="mb-1">
                <label class="form-label">Custom श्रेणी (अगर कोई हो)</label>
                <input type="text" class="form-control" placeholder="जैसे: लग्जरी टैक्सी">
            </div>

            <div class="mb-1">
                <label class="form-label">WhatsApp नंबर</label>
                <input type="text" class="form-control" placeholder="10 अंकों का नंबर डालें">
            </div>

            <div class="mb-1">
                <label class="form-label">शहर</label>
                <input type="text" class="form-control" value="झांसी" readonly>
            </div>

            <div class="mb-1">
                <label class="form-label">इलाका चुनें</label>
                <select class="form-select">
                    <option disabled selected>-- इलाका चुनें --</option>
                    <option value="1">रेलवे स्टेशन</option>
                    <option value="2">बस स्टैंड</option>
                    <option value="3">बड़ा बाजार</option>
                </select>
            </div>

            <div class="mb-1">
                <label class="form-label">संपर्क नंबर</label>
                <input type="text" class="form-control" placeholder="मोबाइल नंबर डालें">
            </div>

            <div class="mb-1">
                <label class="form-label">पासवर्ड</label>
                <input type="password" class="form-control" placeholder="पासवर्ड">
            </div>

            <button type="submit" class="btn btn-success btn-sm w-100 mt-4">रजिस्टर करें</button>

            <div class="text-center mt-3">
                <small>पहले से खाता है? <a href="login.html">लॉगिन करें</a></small>
            </div>
        </form>
    </div>
</div>

@endsection