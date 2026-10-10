@extends('layouts.app')
@section('title', 'Data Reseller')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Kelola Akun Reseller</h2>
        <a href="{{ route('reseller.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Data
        </a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-800 text-sm tracking-wide border-b border-pink-100">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Username</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse($resellers as $index => $r)
                <tr class="hover:bg-gray-50 transition last:border-b-0">
                    <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $r->name }}</td>
                    <td class="px-6 py-4">{{ $r->email ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $r->username }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center items-center gap-3">
                            <a href="{{ route('reseller.edit', $r->id) }}" class="w-9 h-9 rounded-full bg-[#e6f4ea] text-[#137333] hover:bg-green-200 flex items-center justify-center transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('reseller.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-full bg-[#fce8e6] text-[#d93025] hover:bg-red-200 flex items-center justify-center transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
