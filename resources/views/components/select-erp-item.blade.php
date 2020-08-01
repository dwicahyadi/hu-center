<div>
    <select name="{{ $name }}" id="" class="form-control select2">
        @forelse($data as $item)
            <option value="{{ $item->nama }}">{{ $item->nama }}</option>
        @empty
            <option value="">Kosong</option>
        @endforelse
    </select>
</div>
