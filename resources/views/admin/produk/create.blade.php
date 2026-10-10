@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <h3 class="text-xl font-bold text-gray-800">Tambah Produk & Resep</h3>
        <a href="{{ route('produk.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex flex-col shadow-sm">
            <div class="flex items-center mb-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">Terjadi Kesalahan:</span>
            </div>
            <ul class="list-disc pl-7 text-sm mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk (Roti/Kue)</label>
                <input type="text" name="nama_produk" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                <input type="text" name="harga" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia Saat Ini</label>
                <input type="number" name="stok" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" value="0" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" title="Berapa porsi/pcs hasil dari 1 resep ini?">Hasil 1 Resep (Pcs)</label>
                <input type="number" name="batch_size" min="1" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" value="1" required>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
            <div id="foto-preview-container" class="mb-2 hidden">
                <img id="foto-preview" src="" alt="Foto Produk" class="h-20 w-20 object-cover rounded shadow-sm">
                <p class="text-xs text-gray-500 mt-1">Foto otomatis dimuat dari produk yang sudah ada.</p>
            </div>
            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
        </div>
        <div class="bg-pink-50/50 p-6 rounded-xl border border-pink-100">
            <h4 class="text-base font-bold text-pink-800 mb-1">Komposisi Resep</h4>
            <p class="text-sm text-pink-600 mb-4">Centang bahan baku yang digunakan dan masukkan jumlahnya.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-80 overflow-y-auto p-3 border border-pink-200 rounded-lg bg-white shadow-inner">
                @foreach($bahanBakus as $b)
                <div class="flex items-center gap-3 p-2 hover:bg-pink-50 rounded-lg transition border border-transparent hover:border-pink-100 {{ $b->stok <= 0 ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <input type="checkbox" id="bahan_{{ $b->id }}" name="bahan_baku_id[{{ $b->id }}]" value="{{ $b->id }}" class="w-5 h-5 text-pink-600 rounded border-gray-300 focus:ring-pink-500 cursor-pointer disabled:bg-gray-200" {{ $b->stok <= 0 ? 'disabled' : '' }}>
                    <label for="bahan_{{ $b->id }}" class="flex-1 text-sm font-medium {{ $b->stok <= 0 ? 'text-gray-400' : 'text-gray-700' }} cursor-pointer select-none">
                        {{ $b->nama_bahan }} 
                        @if($b->stok <= 0)
                            <span class="text-xs text-red-500 font-bold ml-1">(Bahan Baku Habis)</span>
                        @endif
                    </label>
                    
                    @if($b->satuan_kecil)
                        <div class="flex items-center gap-1.5">
                            <input type="number" min="0" onchange="calcQty({{ $b->id }}, {{ $b->nilai_konversi ?? 1 }})" id="besar_{{ $b->id }}" class="w-16 border border-gray-200 p-1.5 rounded-md text-sm outline-none focus:border-pink-500 text-center" placeholder="0" {{ $b->stok <= 0 ? 'disabled' : '' }}>
                            <span class="text-xs font-semibold text-gray-600 min-w-[24px]">{{ $b->satuan }}</span>
                            
                            <input type="number" min="0" onchange="calcQty({{ $b->id }}, {{ $b->nilai_konversi ?? 1 }})" id="kecil_{{ $b->id }}" class="w-20 border border-gray-200 p-1.5 rounded-md text-sm outline-none focus:border-pink-500 text-center" placeholder="0" {{ $b->stok <= 0 ? 'disabled' : '' }}>
                            <span class="text-xs font-semibold text-gray-600 min-w-[32px]">{{ $b->satuan_kecil }}</span>
                            
                            <input type="hidden" name="jumlah_dibutuhkan[{{ $b->id }}]" id="hidden_{{ $b->id }}">
                        </div>
                    @else
                        <div class="flex items-center gap-1">
                            <input type="number" step="0.1" name="jumlah_dibutuhkan[{{ $b->id }}]" class="w-20 border border-gray-200 p-1.5 rounded-md text-sm outline-none focus:border-pink-500" placeholder="Jml" {{ $b->stok <= 0 ? 'disabled' : '' }}>
                            <span class="text-xs text-gray-500 w-10 truncate">{{ $b->satuan }}</span>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-sm transition duration-300 mt-4">Simpan Produk</button>
    </form>
</div>

<script>
    function calcQty(id, konversi) {
        let bsr = parseFloat(document.getElementById('besar_'+id).value) || 0;
        let kcl = parseFloat(document.getElementById('kecil_'+id).value) || 0;
        document.getElementById('hidden_'+id).value = (bsr * konversi) + kcl;
    }

    let debounceTimer;
    document.querySelector('input[name="nama_produk"]').addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const nama = this.value.trim();
        
        if (nama.length > 2) {
            debounceTimer = setTimeout(() => {
                fetch('/admin/api/produk/' + encodeURIComponent(nama))
                .then(res => res.json())
                .then(data => {
                    if (data && data.id) {
                        // Isi harga
                        document.querySelector('input[name="harga"]').value = data.harga;
                        
                        // Tampilkan foto jika ada
                        const previewContainer = document.getElementById('foto-preview-container');
                        const previewImg = document.getElementById('foto-preview');
                        if (data.foto) {
                            previewImg.src = '/storage/' + data.foto;
                            previewContainer.classList.remove('hidden');
                        } else {
                            previewContainer.classList.add('hidden');
                        }
                        
                        // Reset semua checkbox dan input jumlah
                        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                        document.querySelectorAll('input[type="number"][name^="jumlah_dibutuhkan"]').forEach(inp => inp.value = '');
                        
                        // Centang bahan baku sesuai resep
                        if (data.reseps) {
                            data.reseps.forEach(resep => {
                                const cb = document.querySelector('input[type="checkbox"][value="'+resep.bahan_baku_id+'"]');
                                if (cb) {
                                    cb.checked = true;
                                }
                            });
                        }
                    } else {
                        // Reset preview jika tidak ditemukan
                        document.getElementById('foto-preview-container').classList.add('hidden');
                        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    }
                }).catch(err => console.error('Error fetching data:', err));
            }, 500);
        } else {
            document.getElementById('foto-preview-container').classList.add('hidden');
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    });
</script>
@endsection
