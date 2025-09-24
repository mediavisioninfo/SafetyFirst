<!DOCTYPE html>
<html>
<head>
    <title>Email Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#emailModal">Send Email</a>

    <!-- Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1">
      <div class="modal-dialog">
        <form id="emailForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compose Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Template</label>
                        <select name="template_id" id="templateSelect" class="form-select" required>
                            <option value="">Select Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Body</label>
                        <textarea name="body" id="body" class="form-control" rows="5" required></textarea>
                    </div>

                    <button type="button" class="btn btn-secondary" id="previewBtn">Preview</button>

                    <!-- Preview Section -->
                    <div id="previewBox" class="mt-3 border rounded p-2 d-none">
                        <h5 class="border-bottom">Preview</h5>
                        <p><strong>Subject:</strong> <span id="previewSubject"></span></p>
                        <p><strong>Body:</strong></p>
                        <div id="previewBody" style="white-space: pre-line;"></div>
                    </div>

                    <div id="successMsg" class="alert alert-success d-none mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send & Save</button>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#templateSelect').on('change', function() {
    const id = $(this).val();
    if (id) {
        $.get('/template/' + id, function(data) {
            $('#subject').val(data.subject);
            $('#body').val(data.body);
        });
    } else {
        $('#subject').val('');
        $('#body').val('');
    }
});

$('#previewBtn').on('click', function() {
    $('#previewSubject').text($('#subject').val());
    $('#previewBody').text($('#body').val());
    $('#previewBox').removeClass('d-none');
});

$('#emailForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('emails.store') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            $('#successMsg').text(response.message).removeClass('d-none');
            setTimeout(() => {
                $('#emailModal').modal('hide');
                $('#emailForm')[0].reset();
                $('#previewBox').addClass('d-none');
                $('#successMsg').addClass('d-none');
            }, 2000);
        }
    });
});
</script>
</body>
</html>
