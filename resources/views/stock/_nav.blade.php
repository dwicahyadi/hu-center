<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
    <div class="nav navbar-nav mr-auto">
        <a class="nav-item nav-link" href="{{ route('stock.list') }}"><i class="icon-th-list"></i> List</a>
        <a class="nav-item nav-link" href="{{ route('stock.in') }}"><i class="icon-download text-success"></i> Stock In</a>
        <a class="nav-item nav-link" href="#"><i class="icon-upload text-danger"></i> Stock Out</a>
        <a class="nav-item nav-link" href="#"><i class="icon-search "></i> Search</a>
    </div>
    <div class="nav navbar-nav ml-auto">
        <ol class="breadcrumb py-0 mt-2 bg-transparent">
            @forelse($navs as $nav)
                <li class="breadcrumb-item py-0">{{ $nav }}</li>
            @empty
                <li class="breadcrumb-item py-0">HU Center</li>
            @endforelse
        </ol>

    </div>

</nav>
