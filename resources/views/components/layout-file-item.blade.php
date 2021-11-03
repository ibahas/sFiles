<div class="file-item">
    <div class="file-item-select-bg bg-primary"></div>
    <label class="file-item-checkbox custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" />
        <span class="custom-control-label"></span>
    </label>
    @if (isset($file))
    <div class="file-item-img" style="background-image: url({{url('storage' . '/' . $file)}});"></div>
    @else
    <div class="file-item-icon {{$icon}} text-secondary"></div>
    @endif
    <a class="file-item-name" href="{{route('file.show', $id)}}">
        {{$name}}
    </a>
    <div class="file-item-changed">{{$created}}</div>
    <div class="file-item-actions btn-group">
        <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item">Rename</a>
            <a class="dropdown-item">Move</a>
            <a class="dropdown-item">Copy</a>
            <a class="dropdown-item">Remove</a>
        </div>
    </div>
</div>