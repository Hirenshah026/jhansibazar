<div id="image-modal" class="fixed inset-0 z-[100] hidden bg-black/90 flex items-center justify-center p-4 backdrop-blur-sm">
    <button id="close-modal" class="absolute top-5 right-5 text-white text-4xl font-light hover:text-gray-300 transition-all">&times;</button>
    
    <div class="max-w-4xl w-full h-auto flex justify-center">
        <img id="modal-img" src="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain border border-white/10">
    </div>
</div>
@push('script')
<script>
$(document).ready(function() {
    // 1. Image Click Event
    $('.card-img-slide img').on('click', function(e) {
        e.stopPropagation(); // Card click event ko trigger hone se rokne ke liye
        
        const imgSrc = $(this).attr('src'); // Click ki gayi image ka source
        $('#modal-img').attr('src', imgSrc); // Modal image me source set karein
        $('#image-modal').removeClass('hidden').addClass('flex'); // Modal dikhayein
        
        $('body').addClass('overflow-hidden'); // Background scroll rokne ke liye
    });

    // 2. Close Modal (Button click par)
    $('#close-modal, #image-modal').on('click', function(e) {
        // Sirf tab band ho jab background ya close button par click ho (image par nahi)
        if (e.target !== document.getElementById('modal-img')) {
            $('#image-modal').addClass('hidden').removeClass('flex');
            $('body').removeClass('overflow-hidden');
        }
    });

    // 3. Escape key se close
    $(document).keydown(function(e) {
        if (e.keyCode === 27) { // 27 = Escape key
            $('#image-modal').addClass('hidden').removeClass('flex');
            $('body').removeClass('overflow-hidden');
        }
    });
});
</script>
@endpush