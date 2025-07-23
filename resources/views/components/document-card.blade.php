{{-- resources/views/components/document-card.blade.php --}}
@if (!empty($filename))
    <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card">
            <a href="{{ asset('storage/upload/document/' . $folder . '/' . $filename) }}" target="_blank"
                class="text-decoration-none">
                <div class="card-body">
                    <p class="card-text" style="color: green;">
                        <i data-feather="file"></i><strong>{{ $label }}:</strong><br>
                        {{ $filename }}
                    </p>
                </div>
            </a>
        </div>
    </div>
@else
    <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card-body text-danger">
            <p class="card-text" style="color: red;">
                <strong>{{ $label }}: Pending Document</strong>
            </p>
        </div>
    </div>
@endif
