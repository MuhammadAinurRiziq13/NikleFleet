<div class="row align-items-center mb-2">
    <div class="col-sm-8">
        <h1 class="h3 text-gray-800">{{ $breadcrumb->title }}</h1>
    </div>
    <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right small">
            @foreach ($breadcrumb->list as $key => $value)
                @if ($key == count($breadcrumb->list) - 1)
                    <li class="breadcrumb-item text-primary">{{ $value }}</li>
                @else
                    <li class="breadcrumb-item">{{ $value }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</div>
