@extends('layouts.app')
@section('title', 'Kasir Penjualan')
@section('header', 'Input Transaksi Penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full mx-auto">
    <div class="mb-6 flex justify-between items-center border-b pb-4">
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Daftar Produk
        </h3>
        <a href="{{ route('penjualan.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>

    <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-8" id="penjualan-form">
        @csrf
        <!-- Hidden fields that take up no space -->
        <div class="hidden">
            <input type="hidden" name="no_penjualan" value="{{ $no_penjualan }}">
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Product Grid (Left/Main Side) -->
            <div class="flex-1">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <!-- Search Bar -->
                    <div class="flex items-center gap-2 flex-1 w-full">
                        <div class="relative w-full max-w-md">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" id="search-produk" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 shadow-sm bg-white outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                        </div>
                        <button type="button" class="bg-pink-600 hover:bg-pink-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm transition shrink-0">Tampilkan</button>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-sm text-gray-500 hidden md:inline">Urutkan:</span>
                        <select class="rounded-lg border-gray-200 shadow-sm px-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-sm">
                            <option>Populer</option>
                            <option>Terbaru</option>
                            <option>Harga Tertinggi</option>
                            <option>Harga Terendah</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="product-grid">
                    @foreach($produks as $p)
                    <div class="product-card bg-white rounded-xl shadow-sm hover:shadow-lg hover:shadow-pink-100 border border-gray-100 hover:border-pink-200 overflow-hidden flex flex-col group transition-all duration-300" data-name="{{ strtolower($p->nama_produk) }}">
                        <div class="h-44 bg-pink-50 relative overflow-hidden p-2">
                            @if($p->foto)
                                <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama_produk }}" class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full bg-pink-100/50 rounded-lg flex items-center justify-center text-pink-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-grow justify-between text-center">
                            <div>
                                <h4 class="font-bold text-gray-900 line-clamp-1 text-[15px] mb-1">{{ $p->nama_produk }}</h4>
                                <p class="text-pink-600 font-bold text-sm mb-2">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                                @if(auth()->check() && auth()->user()->role == 'admin')
                                <span class="inline-block bg-pink-50 text-pink-600 text-xs px-2 py-1 rounded-md font-semibold border border-pink-100">
                                    Sisa Stok: {{ $p->stok }}
                                </span>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center gap-2">
                                <input type="number" min="1" value="1" class="w-16 px-2 py-2 border border-gray-200 rounded-lg text-center focus:ring-pink-500 focus:border-pink-500 text-sm" id="input-qty-{{ $p->id }}">
                                <button type="button" class="btn-tambah flex-1 bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 rounded-lg shadow-sm transition active:scale-95 flex items-center justify-center gap-2 text-sm" data-id="{{ $p->id }}" data-name="{{ $p->nama_produk }}" data-price="{{ $p->harga }}">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary (Right Side) -->
            <div class="w-full lg:w-[350px] shrink-0">
                <div class="sticky top-24">
                    <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Keranjang Belanja
                    </h4>
                    
                    <div class="border border-pink-100 rounded-2xl overflow-hidden shadow-sm bg-white">
                        <div class="p-4 min-h-[200px] max-h-[350px] overflow-y-auto" id="cart-summary">
                            <div class="h-full flex flex-col items-center justify-center text-pink-300 py-10">
                                <svg class="w-12 h-12 mb-3 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <p class="text-sm italic">Belum ada produk di keranjang</p>
                            </div>
                        </div>
                        <div class="bg-pink-50/50 p-4 border-t border-pink-100">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-bold text-gray-600">Total Pembayaran</span>
                                <span class="font-black text-xl text-pink-600" id="cart-total">Rp 0</span>
                            </div>
                            
                            <!-- hidden inputs container for form submission -->
                            <div id="cart-inputs"></div>

                            <button type="submit" id="submit-btn" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition-all duration-300 opacity-50 cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Checkout Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let cart = {};

    document.querySelectorAll('.btn-tambah').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let name = this.getAttribute('data-name');
            let price = parseInt(this.getAttribute('data-price'));
            let qtyInput = document.getElementById('input-qty-' + id);
            let addedQty = parseInt(qtyInput.value) || 1;
            
            if(!cart[id]) {
                cart[id] = {qty: 0, name: name, price: price};
            }
            cart[id].qty += addedQty;
            updateCart();
            
            // reset input
            qtyInput.value = 1;
            
            // simple animation on button
            let originalText = this.innerHTML;
            this.innerHTML = 'Ditambahkan!';
            this.classList.add('bg-green-500');
            this.classList.remove('bg-pink-600');
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('bg-green-500');
                this.classList.add('bg-pink-600');
            }, 800);
        });
    });

    function updateCart() {
        const inputsContainer = document.getElementById('cart-inputs');
        const summaryContainer = document.getElementById('cart-summary');
        inputsContainer.innerHTML = '';
        summaryContainer.innerHTML = '';
        
        let total = 0;
        let hasItems = false;
        
        for(let id in cart) {
            let item = cart[id];
            
            inputsContainer.innerHTML += `<input type="hidden" name="produk_id[]" value="${id}">`;
            inputsContainer.innerHTML += `<input type="hidden" name="qty[]" value="${item.qty}">`;
            
            let subtotal = item.qty * item.price;
            total += subtotal;
            summaryContainer.innerHTML += `
                <div class="flex flex-col text-sm mb-3 pb-3 border-b border-gray-100 last:border-0 last:pb-0 last:mb-0">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-bold text-gray-800 leading-tight flex-1 pr-2">${item.name}</p>
                        <span class="font-bold text-gray-800">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-500">@ Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                        <div class="flex items-center gap-1 bg-white border border-pink-100 rounded-md px-1 py-1 shadow-sm">
                            <button type="button" class="cart-minus text-pink-400 hover:text-red-500 font-bold w-6 h-6 flex items-center justify-center transition" data-id="${id}">-</button>
                            <input type="number" min="1" class="cart-input-qty font-bold w-12 text-center text-xs text-pink-700 border border-gray-100 rounded focus:border-pink-300 focus:ring-0 p-1" data-id="${id}" value="${item.qty}">
                            <button type="button" class="cart-plus text-pink-400 hover:text-pink-600 font-bold w-6 h-6 flex items-center justify-center transition" data-id="${id}">+</button>
                        </div>
                    </div>
                </div>`;
            hasItems = true;
        }
        
        if(!hasItems) {
            summaryContainer.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-pink-300 py-10">
                    <svg class="w-12 h-12 mb-3 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-sm italic">Belum ada produk di keranjang</p>
                </div>`;
        }
        
        // bind events for dynamic cart buttons
        document.querySelectorAll('.cart-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                if(cart[id]) {
                    cart[id].qty++;
                    updateCart();
                }
            });
        });
        
        document.querySelectorAll('.cart-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                if(cart[id] && cart[id].qty > 0) {
                    cart[id].qty--;
                    if(cart[id].qty === 0) delete cart[id];
                    updateCart();
                }
            });
        });
        
        document.querySelectorAll('.cart-input-qty').forEach(input => {
            input.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let newQty = parseInt(this.value);
                if(newQty > 0) {
                    if(cart[id]) {
                        cart[id].qty = newQty;
                        updateCart();
                    }
                } else {
                    delete cart[id];
                    updateCart();
                }
            });
        });
        
        document.getElementById('cart-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        
        const submitBtn = document.getElementById('submit-btn');
        if(hasItems) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('hover:-translate-y-0.5');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('hover:-translate-y-0.5');
        }
    }

    // Simple search functionality
    document.getElementById('search-produk').addEventListener('input', function() {
        let keyword = this.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            if(card.getAttribute('data-name').includes(keyword)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection
