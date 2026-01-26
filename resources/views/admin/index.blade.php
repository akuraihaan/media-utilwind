@extends('admin.layout')
@section('title','Manage Courses')

@section('content')
<h1 class="text-2xl font-bold mb-6">Courses</h1>

<a href="{{ route('admin.courses.create') }}"
   class="inline-block mb-6 px-6 py-3 rounded-xl bg-cyan-400 text-black font-semibold">
  + Tambah Course
</a>

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
    <a href="{{ route('admin.courses.edit',$c) }}">Edit</a>
    <form method="POST" action="{{ route('admin.courses.destroy',$c) }}">
      @csrf @method('DELETE')
      <button class="text-red-400">Hapus</button>
    </form>
  </td>
</tr>
@endforeach
</tbody>
</table>
@endsection
