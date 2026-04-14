@extends('front_layout.main')

@section('content')
<div class="min-h-screen bg-gray-50 pb-20">
    <div class="h-48 bg-indigo-600 rounded-b-[3rem] shadow-lg relative">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
    </div>

    <div class="px-6 -mt-24 relative z-10">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="relative inline-block">
                <img src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff&size=128" 
                     class="w-24 h-24 rounded-2xl border-4 border-white shadow-md mx-auto object-cover" 
                     alt="Profile">
                <button class="absolute bottom-0 right-0 bg-indigo-600 text-white p-1.5 rounded-lg border-2 border-white shadow-sm">
                    <i class="fa-solid fa-camera text-xs"></i>
                </button>
            </div>
            
            <h1 class="text-xl font-bold text-gray-800 mt-4" id="userName">User Name</h1>
            <p class="text-gray-400 text-sm font-medium" id="displayMobile">+91 XXXXXXXXXX</p>

            <div class="flex items-center justify-around mt-8 py-4 border-t border-gray-50">
                <div>
                    <span class="block text-lg font-black text-indigo-600">12</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Total Spins</span>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <div>
                    <span class="block text-lg font-black text-green-500">₹250</span>
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Total Won</span>
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            <div class="bg-white flex items-center justify-between p-4 rounded-2xl shadow-sm border border-gray-50 group active:bg-gray-50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Personal Info</h3>
                        <p class="text-[10px] text-gray-400">Update your account details</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-indigo-600 text-xs"></i>
            </div>

            <div class="bg-white flex items-center justify-between p-4 rounded-2xl shadow-sm border border-gray-50 group active:bg-gray-50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">My Rewards</h3>
                        <p class="text-[10px] text-gray-400">View win history & withdrawals</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-green-600 text-xs"></i>
            </div>

            <button onclick="logoutUser()" class="w-full bg-white flex items-center justify-between p-4 rounded-2xl shadow-sm border border-red-50 text-red-500 active:bg-red-50 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>
                    <h3 class="text-sm font-bold">Logout</h3>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // LocalStorage se mobile number uthayein
        const storedMobile = localStorage.getItem('user_mobile');
        if(storedMobile) {
            document.getElementById('displayMobile').innerText = '+91 ' + storedMobile;
        } else {
            // Agar number nahi hai toh home par bhej do
            window.location.href = '/';
        }
    });

    function logoutUser() {
        localStorage.removeItem('user_mobile');
        window.location.href = '/';
    }
</script>
@endsection