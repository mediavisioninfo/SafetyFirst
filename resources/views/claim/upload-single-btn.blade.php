<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .upload-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .upload-section h2 {
            margin-top: 0;
            color: #444;
        }

        .file-input {
            margin-bottom: 10px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .preview-item img,
        .preview-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
        }

        .upload-btn {
            background-color: #0d6efd;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .upload-btn:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .progress {
            margin-top: 10px;
            display: none;
        }

        .progress-bar {
            height: 5px;
            background-color: #0d6efd;
            width: 0%;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            position: relative;
            margin: 10% auto;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            background-color: #fff;
            border-radius: 5px;
        }

        .modal video {
            width: 100%;
            max-height: 50vh;
            object-fit: cover;
        }

        .modal button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .vehicle-number-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .vehicle-number-input {
            width: 30%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .vehicle-preview {
            margin-top: 10px;
            font-weight: bold;
        }

        .cause-of-accident-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .cause-of-accident-textarea {
            width: 90%;
            height: 150px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        .word-count {
            margin-top: 5px;
            font-size: 0.9em;
            color: #666;
        }
        .individual-upload-btn {
            display: none !important;
        }
        
        #master-progress {
            display: none;
        }
        
        .progress-text {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Insurance Claim - Upload Documents</h1>
        <div class="upload-section">
            <h2>Video</h2>
            <p>
                <strong>
                    Make a video that starts with the number plate and chassis number plate,
                    and includes a 360-degree video of the vehicle.
                </strong>
            </p>
            <button id="capture-video" class="upload-btn">Capture Video</button>
            <input type="text" id="vehicle-number" class="vehicle-number-input"
                placeholder="Enter your vehicle number">
            <button class="upload-btn individual-upload-btn" id="upload-vehicle-number">Submit</button>
            <div id="vehicle-preview" class="vehicle-preview"></div>
            <div id="video-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="video" style="display:none;">Upload Video</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="cause-of-accident-section">
            <h2>Cause of Accident (Description)</h2>
            <textarea id="cause-of-accident" class="cause-of-accident-textarea" maxlength="900"
                placeholder="Describe the cause of the accident in 150 words or less"></textarea>
            <div id="word-count" class="word-count">0 / 150 words</div>
            <button class="upload-btn individual-upload-btn" id="submit-cause-of-accident">Submit Description</button>
        </div>

        <div class="upload-section">
            <h2>FIR Copy</h2>
            <label for="fir-copy-select">Do you have an FIR copy?</label>
            <select id="fir-copy-select" class="file-input">
                <option value="" selected disabled>Select Yes or No</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <div id="fir-upload-section" style="display:none;">
                <input type="file" id="fir-copy" class="file-input" accept="image/*">
                <div id="fir-copy-preview" class="preview-container"></div>
                <button class="upload-btn individual-upload-btn" data-type="fir-copy" disabled>Upload FIR Copy</button>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Aadhaar Card</h2>
            <input type="file" id="aadhaar" class="file-input" multiple accept="image/*">
            <div id="aadhaar-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="aadhaar">Upload Aadhaar</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>RC Book</h2>
            <input type="file" id="rcbook" class="file-input" multiple accept="image/*">
            <div id="rcbook-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="rcbook">Upload RC Book</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Driving License</h2>
            <input type="file" id="dl" class="file-input" multiple accept="image/*">
            <div id="dl-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="dl">Upload Driving License</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Insurance</h2>
            <input type="file" id="insurance" class="file-input" accept="application/pdf">
            <div id="insurance-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="insurance">Upload Insurance</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>


        <div class="upload-section">
            <h2>Claim Form</h2>
            <input type="file" id="claimform" class="file-input" accept="application/pdf">
            <div id="claimform-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="claimform">Upload Claim Form</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Claim Intimation</h2>
            <input type="file" id="claimintimation" class="file-input" accept="application/pdf">
            <div id="claimintimation-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="claimintimation">Upload Claim Intimation</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Satisfaction Voucher</h2>
            <input type="file" id="satisfactionvoucher" class="file-input" accept="application/pdf">
            <div id="satisfactionvoucher-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="satisfactionvoucher">Upload Satisfaction Voucher</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Consent Form</h2>
            <input type="file" id="consentform" class="file-input" accept="application/pdf">
            <div id="consentform-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="consentform">Upload Consent Form</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Capture Photos of Damage Vehicle</h2>
            <button id="capture-photo" class="upload-btn">Capture Photo</button>
            <div id="photo-preview" class="preview-container"></div>
            <button class="upload-btn individual-upload-btn" data-type="photos" style="display:none;">Upload Photos</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>
        <div class="upload-section" style="text-align: center;">
            <button id="master-upload-btn" class="upload-btn" style="font-size: 1.2em; padding: 15px 30px;">
                Submit All Documents
            </button>
            <div id="master-progress" style="margin-top: 20px;">
                <div class="progress-text" style="margin-bottom: 10px;"></div>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="camera-modal" class="modal">
        <div class="modal-content">
            <video id="camera-feed" autoplay playsinline></video>
            <button id="capture-button">Capture</button>
            <button id="switch-camera">Switch Camera</button>
        </div>
    </div>

    <input type="hidden" id="claim-id" value="{{ $claimId }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <!-- Include your JavaScript file here -->
    <script>
        $(document).ready(function() {
    const uploadDocumentUrl = "{{ route('upload.document') }}";
    let uploadedDocuments = {
        aadhaar: false,
        rcbook: false,
        dl: false,
        insurance: false,
        photos: false,
        video: false,
        causeOfAccident: false,
        claimform: false,
        claimintimation: false,
        satisfactionvoucher: false,
        consentform: false,
        firCopy: false
    };

    // Existing variables
    let photoCount = 0;
    const maxPhotos = 20;
    let mediaRecorder;
    let recordedChunks = [];
    let currentFacingMode = 'environment';

    // Function to check if all documents are uploaded
    function allDocumentsUploaded() {
        const allUploaded = Object.values(uploadedDocuments).every(value => value === true);
        console.log('Upload status:', uploadedDocuments);
        return allUploaded;
    }

    // Function to show the final 'Thank you' message
    function showThankYouMessage() {
        $('.container').html('<h2 class="text-center">Thank you for submitting your documents!</h2>');
    }

    // Master upload button functionality
    $('#master-upload-btn').on('click', async function() {
        const button = $(this);
        const progressText = $('#master-progress .progress-text');
        const progressBar = $('#master-progress .progress-bar');
        const masterProgress = $('#master-progress');
        
        button.prop('disabled', true);
        masterProgress.show();

        // Validation checks
        const validationErrors = [];
        
        // Required field validations
        if (!$('#vehicle-number').val().trim()) {
            validationErrors.push('Please enter vehicle number');
        }
        
        if (!$('#cause-of-accident').val().trim()) {
            validationErrors.push('Please enter cause of accident');
        }

        // File upload validations
        if (!$('#aadhaar')[0].files.length) {
            validationErrors.push('Please upload Aadhaar card');
        }

        if (!$('#rcbook')[0].files.length) {
            validationErrors.push('Please upload RC Book');
        }

        if (!$('#dl')[0].files.length) {
            validationErrors.push('Please upload Driving License');
        }

        if (!$('#insurance')[0].files.length) {
            validationErrors.push('Please upload Insurance document');
        }

        if (!$('#claimform')[0].files.length) {
            validationErrors.push('Please upload Claim Form');
        }

        if (!$('#claimintimation')[0].files.length) {
            validationErrors.push('Please upload Claim Intimation');
        }

        if (!$('#satisfactionvoucher')[0].files.length) {
            validationErrors.push('Please upload Satisfaction Voucher');
        }

        if (!$('#consentform')[0].files.length) {
            validationErrors.push('Please upload Consent Form');
        }

        // FIR validation based on selection
        if ($('#fir-copy-select').val() === 'yes' && !$('#fir-copy')[0].files.length) {
            validationErrors.push('Please upload FIR copy');
        }

        // Video and Photo validations
        if (!$('#video-preview video').length) {
            validationErrors.push('Please capture and upload a video');
        }

        if (!$('#photo-preview .preview-item').length) {
            validationErrors.push('Please capture and upload photos');
        }
        
        if (validationErrors.length > 0) {
            alert('Please complete the following:\n' + validationErrors.join('\n'));
            button.prop('disabled', false);
            masterProgress.hide();
            return;
        }

        // Array of upload tasks
        const uploadTasks = [
            {
                type: 'vehicle_number',
                action: async () => {
                    const vehicleNumber = $('#vehicle-number').val().trim();
                    await submitVehicleNumber(vehicleNumber);
                }
            },
            {
                type: 'cause_of_accident',
                action: async () => {
                    const causeOfAccident = $('#cause-of-accident').val().trim();
                    await submitCauseOfAccident(causeOfAccident);
                }
            },
            {
                type: 'aadhaar',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#aadhaar')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'aadhaar');
                }
            },
            {
                type: 'rcbook',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#rcbook')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'rcbook');
                }
            },
            {
                type: 'dl',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#dl')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'dl');
                }
            },
            {
                type: 'insurance',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#insurance')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'insurance');
                }
            },
            {
                type: 'claimform',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#claimform')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'claimform');
                }
            },
            {
                type: 'claimintimation',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#claimintimation')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'claimintimation');
                }
            },
            {
                type: 'satisfactionvoucher',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#satisfactionvoucher')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'satisfactionvoucher');
                }
            },
            {
                type: 'consentform',
                action: async () => {
                    const formData = new FormData();
                    const files = $('#consentform')[0].files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('files[]', files[i]);
                    }
                    await uploadFiles(formData, 'consentform');
                }
            },
            {
                type: 'fir-copy',
                action: async () => {
                    if ($('#fir-copy-select').val() === 'yes') {
                        const formData = new FormData();
                        const files = $('#fir-copy')[0].files;
                        for (let i = 0; i < files.length; i++) {
                            formData.append('files[]', files[i]);
                        }
                        await uploadFiles(formData, 'fir-copy');
                    }
                }
            },
            {
                type: 'video',
                action: async () => {
                    if ($('#video-preview video').length) {
                        const formData = new FormData();
                        const videoBlob = new Blob(recordedChunks, { type: 'video/mp4' });
                        formData.append('files[]', videoBlob, 'captured_video.mp4');
                        await uploadCapturedMedia(formData, 'video');
                    }
                }
            },
            {
                type: 'photos',
                action: async () => {
                    if ($('#photo-preview .preview-item').length) {
                        const formData = new FormData();
                        await Promise.all($('#photo-preview .preview-item').map(async function(index, item) {
                            const photoData = $(item).data('photoData');
                            const response = await fetch(photoData.imageDataUrl);
                            const blob = await response.blob();
                            formData.append(`files[]`, blob, `photo_${index + 1}.jpg`);
                            formData.append(`geotags[${index}]`, JSON.stringify(photoData.geotag));
                            formData.append(`captureTimes[${index}]`, photoData.captureTime);
                        }));
                        await uploadCapturedMedia(formData, 'photos');
                    }
                }
            }
        ];

        // Process uploads sequentially
        try {
            for (let i = 0; i < uploadTasks.length; i++) {
                const task = uploadTasks[i];
                progressText.text(`Uploading ${task.type.replace(/-/g, ' ')} (${i + 1}/${uploadTasks.length})`);
                progressBar.css('width', `${(i / uploadTasks.length) * 100}%`);
                
                await task.action();
                console.log(`Completed upload of ${task.type}`);
            }

            // All uploads completed successfully
            progressBar.css('width', '100%');
            progressText.text('All documents uploaded successfully!');
            setTimeout(() => {
                showThankYouMessage();
            }, 1000);

        } catch (error) {
            console.error('Upload failed:', error);
            alert('An error occurred during upload. Please try again.');
            button.prop('disabled', false);
            masterProgress.hide();
        }
    });

    // Upload functions
    function submitVehicleNumber(vehicleNumber) {
        return new Promise((resolve, reject) => {
            axios.post(uploadDocumentUrl, {
                claim_id: $('#claim-id').val(),
                vehicle_number: vehicleNumber,
                document_type: 'vehicle_number'
            })
            .then(() => {
                uploadedDocuments.vehicleNumber = true;
                resolve();
            })
            .catch(reject);
        });
    }

    function submitCauseOfAccident(causeOfAccident) {
        return new Promise((resolve, reject) => {
            axios.post(uploadDocumentUrl, {
                claim_id: $('#claim-id').val(),
                cause_of_accident: causeOfAccident,
                document_type: 'cause_of_accident'
            })
            .then(() => {
                uploadedDocuments.causeOfAccident = true;
                resolve();
            })
            .catch(reject);
        });
    }

    function uploadFiles(formData, type) {
        return new Promise((resolve, reject) => {
            formData.append('claim_id', $('#claim-id').val());
            formData.append('document_type', type);

            axios.post(uploadDocumentUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                uploadedDocuments[type] = true;
                resolve();
            })
            .catch(reject);
        });
    }

    function uploadCapturedMedia(formData, type) {
        return new Promise((resolve, reject) => {
            formData.append('claim_id', $('#claim-id').val());
            formData.append('document_type', type);

            axios.post(uploadDocumentUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                uploadedDocuments[type] = true;
                if (type === 'photos') {
                    $('#photo-preview').html('');
                    photoCount = 0;
                } else if (type === 'video') {
                    $('#video-preview').html('');
                    recordedChunks = [];
                }
                resolve();
            })
            .catch(reject);
        });
    }

    // Media capture functions (keep your existing implementations)
    function getGeolocation() {
        return new Promise((resolve, reject) => {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    error => {
                        console.warn("Geolocation error:", error);
                        resolve(null);
                    }
                );
            } else {
                console.warn("Geolocation is not supported by this browser.");
                resolve(null);
            }
        });
    }

    // Keep all your existing event handlers and helper functions for:
    // - Photo/video capture
    // - Preview functionality
    // - File input change handlers
    // - Modal controls
    // Just remove their individual upload buttons and functionality

        // Function to validate file types
            function validateFileType(fileInput, acceptedTypes) {
                const files = fileInput[0].files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (!acceptedTypes.includes(file.type)) {
                        alert(
                            `Invalid file type for ${fileInput.attr('id')}. Please upload a ${acceptedTypes.join(', ')} file.`);
                        fileInput.val('');
                        return false;
                    }
                }
                return true;
            }

            // Add event listeners for file input fields
            $('#consentform, #satisfactionvoucher, #claimintimation, #claimform, #insurance').on('change',
            function() {
                validateFileType($(this), ['application/pdf']);
            });

            $('#aadhaar, #rcbook, #dl').on('change', function() {
                validateFileType($(this), ['image/jpeg', 'image/png']);
            });
    // Word count functionality
    $('#cause-of-accident').on('input', function() {
        const words = this.value.match(/\S+/g) || [];
        const wordCount = words.length;
        $('#word-count').text(wordCount + ' / 150 words');

        if (wordCount > 150) {
            const truncated = words.slice(0, 150).join(' ');
            this.value = truncated;
            $('#word-count').text('150 / 150 words');
        }
    });
     // FIR Copy Select Logic
     $('#fir-copy-select').on('change', function() {
                const selectedOption = $(this).val();
                if (selectedOption === 'yes') {
                    $('#fir-upload-section').show(); // Show FIR upload section if 'Yes' is selected
                    $('#fir-copy').prop('required', true); // Make the FIR file input mandatory
                    $('.upload-btn[data-type="fir-copy"]').prop('disabled', false); // Enable upload button
                } else {
                    $('#fir-upload-section').hide(); // Hide FIR upload section if 'No' is selected
                    $('#fir-copy').prop('required', false); // Make FIR file input not mandatory
                    $('.upload-btn[data-type="fir-copy"]').prop('disabled', true); // Disable upload button
                }
            });
    $('#capture-photo').on('click', function() {
                if (photoCount < maxPhotos) {
                    captureMedia('image');
                }
            });

    $('#capture-video').on('click', function() {
                captureMedia('video');
            });
    // File preview functionality with size validation
            $('input[type="file"]').on('change', function() {
                const files = this.files;
                const previewId = `${$(this).attr('id')}-preview`;
                const previewContainer = $(`#${previewId}`);
                previewContainer.html('');

                const maxSize = 1024 * 1024; // 1 MB

                $.each(files, function(index, file) {
                    if (file.size > maxSize) {
                        alert(
                            `File "${file.name}" is larger than 1 MB. Please choose a smaller file.`
                        );
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = $('<div class="preview-item"></div>');
                        if (file.type.startsWith('image/')) {
                            previewItem.append(`<img src="${e.target.result}" alt="Preview">`);
                        } else if (file.type === 'application/pdf') {
                            previewItem.append('<i class="fas fa-file-pdf fa-3x"></i>');
                        }
                        previewItem.append('<button class="remove-btn">&times;</button>');
                        previewContainer.append(previewItem);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Remove preview item
            $(document).on('click', '.remove-btn', function() {
                $(this).parent('.preview-item').remove();
            });

 // Modify the capturePhoto function
            async function capturePhoto(videoElement, stream) {
                const canvas = document.createElement('canvas');
                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;
                canvas.getContext('2d').drawImage(videoElement, 0, 0);

                const imageDataUrl = canvas.toDataURL('image/jpeg');

                // Get geolocation data
                const geolocation = await getGeolocation();

                // Get current date and time
                const captureTime = new Date().toISOString();

                const photoData = {
                    imageDataUrl: imageDataUrl,
                    geotag: geolocation,
                    captureTime: captureTime
                };

                const previewItem = createPhotoPreview(photoData);
                $('#photo-preview').append(previewItem);

                stream.getTracks().forEach(track => track.stop());
                document.getElementById('camera-modal').style.display = 'none';

                photoCount++;
                if (photoCount === maxPhotos) {
                    $('#capture-photo').prop('disabled', true);
                }
                $('.upload-btn[data-type="photos"]').show();
            }

            // Modify the createPhotoPreview function
            function createPhotoPreview(photoData) {
                const previewItem = $('<div class="preview-item saved"></div>');
                previewItem.append(`<img src="${photoData.imageDataUrl}" alt="Captured photo">`);
                previewItem.append('<button class="btn btn-danger remove-btn">&times;</button>');

                // Store the full photoData object with the preview item
                previewItem.data('photoData', photoData);

                return previewItem;
            }


            // Function to start video recording
            function startVideoRecording(stream) {
                recordedChunks = []; // Reset recorded chunks array

                try {
                    mediaRecorder = new MediaRecorder(stream, {
                        mimeType: 'video/mp4'
                    });
                } catch (error) {
                    console.error("MediaRecorder initialization error:", error);
                    alert(
                        "Your browser may not support the required video format. Please try using a different browser."
                    );
                    return;
                }

                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data); // Save recorded chunks
                    }
                };

                mediaRecorder.onstop = function() {
                    const blob = new Blob(recordedChunks, {
                        type: 'video/mp4'
                    });
                    const videoUrl = URL.createObjectURL(blob);
                    const videoPreview = document.createElement('video');
                    videoPreview.src = videoUrl;
                    videoPreview.controls = true;
                    videoPreview.playsInline = true; // iOS specific setting to play inline
                    videoPreview.style.width = '100%';
                    $('#video-preview').html(videoPreview);

                    const uploadButton = $('.upload-btn[data-type="video"]');
                    uploadButton.show();
                };

                try {
                    mediaRecorder.start();
                    console.log("Recording started...");
                } catch (error) {
                    console.error("Failed to start recording:", error);
                    alert(
                        "An error occurred while trying to start recording. Please check the console for details."
                    );
                }
            }

            // Function to stop video recording
            function stopVideoRecording() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                    console.log("Recording stopped.");
                }
                if (mediaRecorder && mediaRecorder.stream) {
                    mediaRecorder.stream.getTracks().forEach(track => track.stop()); // Stop all video streams
                }
                $('#camera-modal').hide();
            }
            // Switch Camera (Front/Back) Functionality
            $('#switch-camera').on('click', function() {
                currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
                captureMedia('image');
            });

            // Close Modal on Outside Click
            window.onclick = function(event) {
                const modal = document.getElementById('camera-modal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };

            function captureMedia(mediaType) {
                const constraints = {
                    video: {
                        facingMode: 'environment'
                    },
                    audio: mediaType === 'video'
                };

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function(stream) {
                        const videoElement = document.getElementById('camera-feed');
                        videoElement.srcObject = stream;
                        videoElement.play();

                        const captureButton = document.getElementById('capture-button');
                        const modal = document.getElementById('camera-modal');
                        modal.style.display = 'block';

                        if (mediaType === 'image') {
                            captureButton.textContent = 'Capture Photo';
                            captureButton.onclick = function() {
                                capturePhoto(videoElement, stream);
                            };
                        } else {
                            captureButton.textContent = 'Start Recording';
                            captureButton.onclick = function() {
                                if (captureButton.textContent === 'Start Recording') {
                                    startVideoRecording(stream);
                                    captureButton.textContent = 'Stop Recording';
                                } else {
                                    stopVideoRecording();
                                    captureButton.textContent = 'Start Recording';
                                }
                            };
                        }
                    })
                    .catch(function(error) {
                        console.error("Error accessing the camera:", error);
                        alert(
                            "Unable to access the camera. Please make sure you've granted the necessary permissions."
                        );
                    });
            }

            
           // Function to upload captured media (photos or video)
            function uploadCapturedMedia(formData, type) {
                formData.append('claim_id', $('#claim-id').val());
                formData.append('document_type', type);
                const progressBar = $(`.upload-btn[data-type="${type}"]`).siblings('.progress').find(
                    '.progress-bar');
                progressBar.parent().show();

                axios.post(uploadDocumentUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent
                            .total);
                        progressBar.css('width', percentCompleted + '%').attr('aria-valuenow',
                            percentCompleted);
                    }
                }).then(function(response) {
                    alert(`${type} uploaded successfully!`);
                    progressBar.parent().hide();
                    if (type === 'photos') {
                        $('#photo-preview').html('');
                        photoCount = 0;
                        $('#capture-photo').prop('disabled', false);
                    } else if (type === 'video') {
                        $('#video-preview').html('');
                        recordedChunks = [];
                    }
                    $(`.upload-btn[data-type="${type}"]`).hide();

                    uploadedDocuments[type] = true;

                    if (allDocumentsUploaded()) {
                        showThankYouMessage();
                    }
                }).catch(function(error) {
                    progressBar.parent().hide();
                });
            }

        });
   
    </script>
</body>

</html>
