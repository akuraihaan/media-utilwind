@extends('admin.layout')
@section('title','Manajemen Materi')

@section('content')
<h1 class="text-2xl font-bold mb-6">Manajemen Materi</h1>

@if(\Illuminate\Support\Facades\Route::has('admin.courses.create'))
  <a href="{{ route('admin.courses.create') }}"
     class="inline-block mb-6 px-6 py-3 rounded-xl bg-cyan-400 text-black font-semibold">
    + Tambah Materi
  </a>
@endif

<table class="w-full text-sm">
<thead>
<tr class="border-b border-white/10">
  <th>Judul</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
@foreach($courses as $c)
<tr class="border-b border-white/5">
  <td>{{ $c->title }}</td>
  <td class="flex gap-3">
    @if(\Illuminate\Support\Facades\Route::has('admin.courses.edit'))
      <a href="{{ route('admin.courses.edit',$c) }}">Edit</a>
    @endif
    @if(\Illuminate\Support\Facades\Route::has('admin.courses.destroy'))
      <form method="POST" action="{{ route('admin.courses.destroy',$c) }}">
        @csrf @method('DELETE')
        <button class="text-red-400">Hapus</button>
      </form>
    @endif
  </td>
</tr>
@endforeach
</tbody>
</table>
@endsection
