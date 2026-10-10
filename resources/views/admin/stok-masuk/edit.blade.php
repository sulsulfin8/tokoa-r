@extends('layouts.app')
@section('title', 'Edit Stok Masuk')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center border-b border-gray-100 pb-4">
        <h3 class="text-xl font-bold text-gray-800">Edit Pembelian Bahan Baku</h3>
        <a href="{{ route('stok-masuk.index') }}" class="p-2 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shadow-sm" title="Kembali"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
    </div>
    <form action="{{ route('stok-masuk.update', $stokMasuk->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal_masuk" value="{{ $stokMasuk->tanggal }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bahan Baku</label>
            <select name="bahan_baku_id" id="bahan_baku_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
                @foreach($bahanBakus as $b) 
                    <option value="{{ $b->id }}" data-harga="{{ $b->harga }}" data-satuan="{{ $b->satuan }}" {{ $stokMasuk->bahan_baku_id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_bahan }} (Satuan: {{ $b->satuan }}) - Sisa: {{ $b->stok }} {{ $b->satuan }}
                    </option> 
                @endforeach
            </select>
            <div class="mt-1.5 flex items-center gap-3 text-xs text-gray-500">
                <span>Satuan Pembelian: <strong id="info-satuan-bahan" class="text-pink-600 font-semibold">-</strong></span>
                <span>•</span>
                <span>Harga Satuan: <strong id="info-harga-bahan" class="text-gray-800 font-semibold">-</strong></span>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pemasok</label>
            <select name="pemasok_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
                @foreach($pemasoks as $p) 
                    <option value="{{ $p->id }}" {{ $stokMasuk->pemasok_id == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_pemasok }}
                    </option> 
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jumlah Stok Masuk (<span id="label-satuan" class="text-pink-600 font-bold uppercase">{{ $stokMasuk->bahanBaku->satuan ?? 'kg' }}</span>)
            </label>
            <div class="relative rounded-lg shadow-sm">
                <input type="number" min="1" step="any" name="jumlah" id="jumlah" value="{{ $stokMasuk->jumlah }}" class="w-full pl-4 pr-20 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span id="badge-satuan" class="text-xs font-bold text-pink-700 uppercase bg-pink-100 px-2.5 py-1 rounded-md border border-pink-200">{{ $stokMasuk->bahanBaku->satuan ?? 'kg' }}</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1.5">
                *Masukkan jumlah pembelian dalam satuan <strong id="keterangan-satuan" class="text-pink-600 font-bold">{{ $stokMasuk->bahanBaku->satuan ?? 'kg' }}</strong> sesuai satuan master bahan baku.
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Biaya (Otomatis Dihitung)</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-bold text-sm">Rp</span>
                </div>
                <input type="text" name="total_biaya" id="total_biaya" value="{{ number_format($stokMasuk->total_biaya, 0, ',', '.') }}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value) this.value = parseInt(this.value, 10).toLocaleString('id-ID');" class="w-full pl-11 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-pink-500 outline-none transition" required>
            </div>
            <p class="text-xs text-gray-500 mt-1">*Anda masih bisa merubah total ini jika ada diskon dari pemasok.</p>
        </div>
        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-sm transition duration-300 mt-4">Simpan Perubahan</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bahanSelect = document.getElementById('bahan_baku_id');
        const jumlahInput = document.getElementById('jumlah');
        const totalInput = document.getElementById('total_biaya');
        const labelSatuan = document.getElementById('label-satuan');
        const badgeSatuan = document.getElementById('badge-satuan');
        const keteranganSatuan = document.getElementById('keterangan-satuan');
        const infoSatuanBahan = document.getElementById('info-satuan-bahan');
        const infoHargaBahan = document.getElementById('info-harga-bahan');

        function updateBahanInfo() {
            const option = bahanSelect.options[bahanSelect.selectedIndex];
            if (!option) return;

            const satuan = option.getAttribute('data-satuan') || 'kg';
            const harga = parseInt(option.getAttribute('data-harga')) || 0;

            if (labelSatuan) labelSatuan.textContent = satuan;
            if (badgeSatuan) badgeSatuan.textContent = satuan;
            if (keteranganSatuan) keteranganSatuan.textContent = satuan;
            if (infoSatuanBahan) infoSatuanBahan.textContent = satuan;
            if (infoHargaBahan) infoHargaBahan.textContent = 'Rp ' + harga.toLocaleString('id-ID') + ' / ' + satuan;
        }

        function hitungTotal() {
            if (!bahanSelect.value || !jumlahInput.value) return;
            const option = bahanSelect.options[bahanSelect.selectedIndex];
            const harga = parseInt(option.getAttribute('data-harga')) || 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const total = Math.round(harga * jumlah);
            totalInput.value = total.toLocaleString('id-ID');
        }

        bahanSelect.addEventListener('change', function() {
            updateBahanInfo();
            hitungTotal();
        });
        jumlahInput.addEventListener('input', hitungTotal);

        updateBahanInfo();
    });
</script>
@endsection
