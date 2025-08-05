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
        
        .preview-image {
            max-width: 180px; /* Set the maximum width for the image */
            max-height: 150px; /* Set the maximum height for the image */
            width: auto; /* Maintain the aspect ratio */
            height: auto; /* Maintain the aspect ratio */
            border-radius: 4px; /* Add slight rounded corners */
            border: 1px solid #ccc; /* Add a light border around the image */
            object-fit: cover; /* Ensure the image fits within the bounds */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add smooth transitions for hover effects */
        }

        /* Hover effect for the image */
        .preview-image:hover {
            transform: scale(1.05); /* Slight zoom effect on hover */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15); /* Add a shadow effect on hover */
            border-color: #007bff; /* Change border color on hover */
        }

        /* Styling for the PDF preview container */
        .preview-container {
            display: flex; /* Arrange previews in a row */
            flex-wrap: wrap; /* Allow wrapping to the next row */
            gap: 16px; /* Space between previews */
            justify-content: flex-start; /* Align items to the start */
            padding: 16px;
        }

        /* Individual PDF preview styling */
        .pdf-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            border: 1px solid #ccc; /* Light border */
            border-radius: 8px;
            padding: 8px;
            background-color: #f9f9f9; /* Light background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Link for viewing full PDF */
        .pdf-preview .view-link {
            margin-top: 8px;
            text-decoration: none;
            color: #007bff; /* Blue color for the link */
            font-weight: bold;
        }

        .pdf-preview .view-link:hover {
            text-decoration: underline;
        }
        .cause-of-accident-section {
            position: relative;
        }
        .voice-input-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .voice-input-btn:hover {
            background-color: #0056b3;
        }
        .logo-and-title {
            display: flex;
            align-items: center;
            flex-wrap: wrap; /* Allows wrapping for smaller screens */
        }

        .logo {
            height: 80px; /* Adjust as needed */
            margin-right: 15px;
            max-width: 100%; /* Ensures logo scales down on small screens */
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .logo-and-title {
                flex-direction: column; /* Stacks the logo and title vertically */
                align-items: center; /* Center-align for smaller screens */
                text-align: center;
            }

            .logo {
                margin-right: 0; /* Removes right margin for vertical layout */
                margin-bottom: 10px; /* Adds spacing below the logo */
            }

            h1 {
                font-size: 1.5rem; /* Adjust font size for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .logo {
                height: 40px; /* Further reduce logo size for very small screens */
            }

            h1 {
                font-size: 1.2rem; /* Make the title smaller on extra-small screens */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-and-title">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Logo" class="logo">
            <h1>Insurance Claim - Upload Documents</h1>
        </div>
        
        <div class="upload-section">
            <h2>Capture Number Plate</h2>

            @if (!empty($claimData[0]['number_plate_file']))
                @php
                    $photoFiles = json_decode($claimData[0]['number_plate_file'], true); // decode JSON
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $photoFiles = [];
                    }
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('number_plate');
                @endphp

                <!-- Display all number plate images -->
                <div id="number-plate-preview" class="preview-container">
                    @foreach($photoFiles as $photo)
                        @php
                            $filename = $photo['filename'] ?? null;
                            $imageUrl = $filename ? route('secure.image', [$claimHash, $folderCode, 'null', $filename]) : null;
                        @endphp

                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="Number Plate Image" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
                <button id="capture-number-plate" class="upload-btn">Capture Number Plate</button>
            @endif

            @if (!empty($claimData[0]['vehicle_number']))
                <input type="text" id="vehicle-number" value="{{ $claimData[0]['vehicle_number'] }}" class="vehicle-number-input" placeholder="Enter your vehicle number">
            @else
                <input type="text" id="vehicle-number" class="vehicle-number-input" placeholder="Enter your vehicle number">
                <span id="vehicle-number-error" class="validation-error" style="color: red;"></span>
            @endif

            <button class="upload-btn" id="upload-vehicle-number">Submit</button>
            <div id="vehicle-preview" class="vehicle-preview"></div>
            <div id="number-plate-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="number_plate" style="display:none;">Upload Number Plate</button>

            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>
        <div class="upload-section">
            <h2>Video</h2>
            <p>
                <strong>
                    Make a video that starts with the number plate and chassis number plate,
                    and includes a 360-degree video of the vehicle.
                </strong>
            </p>
            @if (!empty($claimData[0]['video_file']))
                    @php
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('video');
                        $filename = $claimData[0]['video_file'];
                        $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                    @endphp
                <div id="video-preview" class="preview-container">
                    <video controls width="400">
                        <source src="{{ $imageUrl }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @else
            <!-- <button id="capture-video" class="upload-btn">Capture Video</button>
            <div id="video-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="video" style="display:none;">Upload Video</button> -->
             <!-- Options for capture or upload -->
             <div class="option-selector" style="margin:5px 0px;">
                    <label>
                        <input type="radio" name="action" value="capture" id="capture-option" checked>
                        Capture Video
                    </label>
                    @if (Auth::check())
                        @if (!empty($user->type === 'operator') || !empty($user->type === 'manager'))
                            <label>
                                <input type="radio" name="action" value="upload" id="upload-option">
                                Upload Existing Video
                            </label>
                        @endif
                    @endif
                </div>
                <!-- Capture option -->
                <div id="capture-container" class="action-container">
                    <button id="capture-video" class="upload-btn">Capture Video</button>
                    <button class="upload-btn" data-type="video" style="display:none;">Upload Video</button> 
                </div>
                <!-- Upload option -->
                <div id="upload-container" class="action-container" style="display: none;">
                    <input type="file" id="existingVideo" class="file-input">
                    <button class="upload-btn" data-type="video">Upload Video</button>
                </div>
                <div id="video-preview" class="preview-container"></div>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="cause-of-accident-section">
            <h2>Cause of Accident (Description)</h2>
            @if(!empty($claimData[0]['cause_of_accident']))
                <textarea id="cause-of-accident" class="cause-of-accident-textarea">{{ $claimData[0]['cause_of_accident'] }}</textarea>
            @else
                <textarea id="cause-of-accident" class="cause-of-accident-textarea" maxlength="900"
                placeholder="Describe the cause of the accident in 150 words or less"></textarea>
            @endif
            <div id="word-count" class="word-count">0 / 150 words</div>
            <button class="upload-btn" id="submit-cause-of-accident">Submit Description</button>
            <!-- Microphone button -->
            <button id="voice-input-btn" class="voice-input-btn" title="Click to Speak">
            🔊
            </button>
        </div>

        <div class="upload-section">
            <h2>FIR Copy</h2>
            @if(!empty($claimData[0]['fir_file']))
                <!-- Convert JSON string to array if needed -->
                @php
                    // Check if it's a JSON string, and decode if necessary
                    if (is_string($claimData[0]['fir_file'])) {
                        $firFiles = json_decode($claimData[0]['fir_file'], true); // Decode JSON string to array
                        // If it's not a valid JSON, use explode for comma-separated string
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $firFiles = explode(',', $claimData[0]['fir_file']);
                        }
                    } else {
                        $firFiles = $claimData[0]['fir_file']; // Already an array
                    }
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('fir');
                @endphp
                <div id="fir-copy-preview" class="preview-container">
                    @foreach($firFiles as $file)
                        @php
                            $filename = $file;
                            $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]); // 'null' placeholder
                        @endphp
                        @if (isset($file))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <label for="fir-copy-select">Do you have an FIR copy?</label>
            <select id="fir-copy-select" class="file-input">
                <option value="" selected disabled>Select Yes or No</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <div id="fir-upload-section" style="display:none;">
                <input type="file" id="fir-copy" class="file-input" accept="image/*">
                <div id="fir-copy-preview" class="preview-container"></div>
                <button class="upload-btn" data-type="fir-copy" disabled>Upload FIR Copy</button>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
            @endif
        </div>

        <div class="upload-section">
            <h2>Aadhaar Card<span style="color: red;">*</span></h2>
            @if(!empty($claimData[0]['aadhaar_files']))
                <!-- Convert JSON string to array if needed -->
                @php
                    // Check if it's a JSON string, and decode if necessary
                    if (is_string($claimData[0]['aadhaar_files'])) {
                        $aadhaarFiles = json_decode($claimData[0]['aadhaar_files'], true); // Decode JSON string to array
                        // If it's not a valid JSON, use explode for comma-separated string
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $aadhaarFiles = explode(',', $claimData[0]['aadhaar_files']);
                        }
                    } else {
                        $aadhaarFiles = $claimData[0]['aadhaar_files']; // Already an array
                    }
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('aadhaar');
                @endphp
                <div id="aadhaar-preview" class="preview-container">
                    @foreach($aadhaarFiles as $file)
                        @php
                            $filename = $file;
                            $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]); // 'null' placeholder
                        @endphp
                        @if (isset($file))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <input type="file" id="aadhaar" class="file-input" multiple accept="image/*" required>
            <div id="aadhaar-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="aadhaar">Upload Aadhaar</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Pan Card<span style="color: red;">*</span></h2>
            @if(!empty($claimData[0]['pancard_file']))
             <!-- Convert JSON string to array if needed -->
             @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['pancard_file'])) {
                    $panCardFiles = json_decode($claimData[0]['pancard_file'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $panCardFiles = explode(',', $claimData[0]['pancard_file']);
                    }
                } else {
                    $panCardFiles = $claimData[0]['pancard_file']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('pan_card');
            @endphp
            <div id="pan-preview" class="preview-container">
                @foreach($panCardFiles as $file)
                    @php
                        $filename = $file;
                        $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                    @endphp
                    @if (isset($file))
                        <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                    @endif
                @endforeach
            </div>
            @else
            <input type="file" id="pan_card" class="file-input" multiple accept="image/*">
            <div id="pan_card-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="pan_card">Upload Pan</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <!-- <div class="upload-section">
            <h2>RC Book</h2>
            <input type="file" id="rcbook" class="file-input" multiple accept="image/*">
            <div id="rcbook-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="rcbook">Upload RC Book</button>
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div> -->

        <div class="upload-section">
            @if(isset($claimData[0]['rcbook_files']) && $claimData[0]['rcbook_files'])
                <h2>RC Book<span style="color: red;">*</span></h2>
            @elseif(isset($claimData[0]['tax_receipt_file']) && isset($claimData[0]['sales_invoice_file']))
                <h2>Tax Receipt & Sales Invoice<span style="color: red;">*</span></h2>
            @else
                <h2>RC Book<span style="color: red;">*</span></h2>
            @endif
            
            @if(!empty($claimData[0]['rcbook_files']) || !empty($claimData[0]['tax_receipt_file']) && !empty($claimData[0]['sales_invoice_file']))
                @if(!empty($claimData[0]['rcbook_files']))
                    <!-- If RC Book exists, show existing files -->
                    @php
                        // Convert JSON string to array if needed
                        if (is_string($claimData[0]['rcbook_files'])) {
                            $rcFiles = json_decode($claimData[0]['rcbook_files'], true); // Decode JSON string to array
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $rcFiles = explode(',', $claimData[0]['rcbook_files']);
                            }
                        } else {
                            $rcFiles = $claimData[0]['rcbook_files']; // Already an array
                        }
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('rcbook');
                    @endphp
                    <div id="rcbook-preview" class="preview-container">
                        @foreach($rcFiles as $file)
                            @php
                                $filename = $file;
                                $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                            @endphp
                            @if (isset($file))
                                <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($claimData[0]['tax_receipt_file']))
                    @php
                        if (is_string($claimData[0]['tax_receipt_file'])) {
                            $taxReceiptFiles = json_decode($claimData[0]['tax_receipt_file'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $taxReceiptFiles = explode(',', $claimData[0]['tax_receipt_file']);
                            }
                        } else {
                            $taxReceiptFiles = $claimData[0]['tax_receipt_file'];
                        }
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('tax_receipt');
                    @endphp
                    <div id="tax_receipt-preview" class="preview-container">
                        @foreach($taxReceiptFiles as $file)
                            @php
                                $filename = $file;
                                $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                            @endphp
                            @if (isset($file))
                                <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($claimData[0]['sales_invoice_file']))
                    @php
                        if (is_string($claimData[0]['sales_invoice_file'])) {
                            $salesInvoiceFiles = json_decode($claimData[0]['sales_invoice_file'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $salesInvoiceFiles = explode(',', $claimData[0]['sales_invoice_file']);
                            }
                        } else {
                            $salesInvoiceFiles = $claimData[0]['sales_invoice_file'];
                        }
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('sales_invoice');
                    @endphp
                    <div id="sales_invoice-preview" class="preview-container">
                        @foreach($salesInvoiceFiles as $file)
                            @php
                                $filename = $file;
                                $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                            @endphp
                            @if (isset($file))
                                <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                            @endif
                        @endforeach
                    </div>
                @endif
            @else
                <!-- If RC Book does not exist, ask for input -->
                <label for="rcbook-select">Do you have an RC Book?</label>
                <select id="rcbook-select" class="file-input">
                    <option value="" selected disabled>Select Yes or No</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <!-- If user selects "Yes", show upload section for RC Book -->
                <div id="rcbook-upload-section" style="display: none;">
                    <h4>Please upload the RC Book:</h4>
                    <input type="file" id="rcbook" class="file-input" multiple accept="image/*">
                    <div id="rcbook-preview" class="preview-container"></div>
                    <span id="rcbook-error" class="validation-error" style="color: red;"></span>
                    <button class="upload-btn" data-type="rcbook">Upload RC Book</button>
                </div>
                <!-- If user selects "No", show Tax Receipt and Sales Invoice options -->
                <div id="alternative-documents" style="display: none;">
                    <h4>If RC Book is unavailable, please upload both the Tax Receipt and the Sales Invoice as alternative documents.</h4>
                    <h4>Tax Receipt</h4>
                        <input type="file" id="tax_receipt" class="file-input" multiple accept="image/*">
                        <div id="tax_receipt-preview" class="preview-container"></div>
                        <button class="upload-btn" data-type="tax_receipt">Upload Tax Receipt</button>
                    <h4>Sales Invoice</h4>
                        <input type="file" id="sales_invoice" class="file-input" multiple accept="image/*">
                        <div id="sales_invoice-preview" class="preview-container"></div>
                        <button class="upload-btn" data-type="sales_invoice">Upload Sales Invoice</button>
                    <span id="alternative-documents-error" class="validation-error" style="color: red;"></span>

                </div>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            @endif
        </div>

        <div class="upload-section">
            <h2>Driving License <span style="color: red;">*</span></h2>
            
            @if(!empty($claimData[0]['dl_files']) || !empty($claimData[0]['other_dl_files']))
                @if(!empty($claimData[0]['dl_files']))
                    <!-- If RC Book exists, show existing files -->
                    @php
                        // Check if it's a JSON string, and decode if necessary
                        if (is_string($claimData[0]['dl_files'])) {
                            $dlFiles = json_decode($claimData[0]['dl_files'], true); // Decode JSON string to array
                            // If it's not a valid JSON, use explode for comma-separated string
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $dlFiles = explode(',', $claimData[0]['dl_files']);
                            }
                        } else {
                            $dlFiles = $claimData[0]['dl_files']; // Already an array
                        }
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('dl');
                    @endphp
                    <h4>Owner Driving License</h4>
                    <div id="dl-preview" class="preview-container">
                        @foreach($dlFiles as $file)
                            @php
                                $filename = $file;
                                $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                            @endphp
                            @if (isset($file))
                                <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($claimData[0]['other_dl_files']))
                    @php
                        if (is_string($claimData[0]['other_dl_files'])) {
                            $otherDlFiles = json_decode($claimData[0]['other_dl_files'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $otherDlFiles = explode(',', $claimData[0]['other_dl_files']);
                            }
                        } else {
                            $otherDlFiles = $claimData[0]['other_dl_files'];
                        }
                        $claimHash = md5($claimId);
                        $folderCode = getFolderCode('other_dl');
                    @endphp
                    <h4>Driver Driving License</h4>
                    <div id="other_dl-preview" class="preview-container">
                        @foreach($otherDlFiles as $file)
                            @php
                                $filename = $file;
                                $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                            @endphp
                            @if (isset($file))
                                <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                            @endif
                        @endforeach
                    </div>
                @endif
            @else
                <!-- DL Owner Selector -->
                <div class="form-group">
                    <label for="dl_owner">Whose Driving License?</label>
                    <select id="dl_owner" name="dl_owner" class="form-control" required>
                        <option value="">-- Select DL Owner --</option>
                        <option value="owner">Owner</option>
                        <option value="other">Driver</option>
                    </select>
                </div>
                <!-- Upload for Owner's DL -->
                <div id="owner-dl-section" class="dl-upload-block" style="display: none;">
                    <h4>Upload Owner DL</h4>
                    <input type="file" id="dl" name="dl[]" class="file-input" multiple accept="image/*">
                    <div id="dl-preview" class="preview-container"></div>
                    <button class="upload-btn" data-type="dl">Upload Owner Driving License</button>
                </div>
                <!-- Upload for Other Person's DL -->
                    <div id="other-dl-section" class="dl-upload-block" style="display: none;">
                        <h4>Upload Driver DL</h4>
                        <input type="file" id="other_dl" name="other_dl[]" class="file-input" multiple accept="image/*">
                        <div id="other_dl-preview" class="preview-container"></div>
                        <button class="upload-btn" data-type="other_dl">Upload Driver Driving License</button>
                    </div>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            @endif
        </div>


        <div class="upload-section">
            <h2>Insurance<span style="color: red;">*</span></h2>
            @if(!empty($claimData[0]['insurance_file']))
            @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['insurance_file'])) {
                    $InsuranceFiles = json_decode($claimData[0]['insurance_file'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $InsuranceFiles = explode(',', $claimData[0]['insurance_file']);
                    }
                } else {
                    $InsuranceFiles = $claimData[0]['insurance_file']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('insurance');
            @endphp
            <div id="insurance-preview" class="preview-container">
                @foreach($InsuranceFiles as $file)
                @php
                    $filename = $file;
                    $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                @endphp
                @if (isset($file))
                    <a href="{{ $imageUrl }}" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF Preview" width="100" height="120">
                        <p>View Full PDF</p>
                    </a>
                @endif
                
                @endforeach
            </div>
            @else
            <input type="file" id="insurance" class="file-input" accept="application/pdf">
            <div id="insurance-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="insurance">Upload Insurance</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>


        <div class="upload-section">
            <h2>Claim Form<span style="color: red;">*</span></h2>
            @if(!empty($claimData[0]['claim_form_file']))
            @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['claim_form_file'])) {
                    $claimFormFiles = json_decode($claimData[0]['claim_form_file'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $claimFormFiles = explode(',', $claimData[0]['claim_form_file']);
                    }
                } else {
                    $claimFormFiles = $claimData[0]['claim_form_file']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('claimform');
            @endphp
            <div id="claimform-preview" class="preview-container">
                @foreach($claimFormFiles as $file)
                @php
                    $filename = $file;
                    $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                @endphp
                @if (isset($file))
                    <a href="{{ $imageUrl }}" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF Preview" width="100" height="120">
                        <p>View Full PDF</p>
                    </a>
                @endif
                @endforeach
            </div>
            @else
            <input type="file" id="claimform" class="file-input" accept="application/pdf">
            <div id="claimform-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="claimform">Upload Claim Form</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Claim Intimation<span style="color: red;">*</span></h2>
            @if(!empty($claimData[0]['claim_intimation_file']))
            @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['claim_intimation_file'])) {
                    $claimIntimationFiles = json_decode($claimData[0]['claim_intimation_file'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $claimIntimationFiles = explode(',', $claimData[0]['claim_intimation_file']);
                    }
                } else {
                    $claimIntimationFiles = $claimData[0]['claim_intimation_file']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('claimintimation');
            @endphp
            <div id="claimintimation-preview" class="preview-container">
                @foreach($claimIntimationFiles as $file)
                @php
                    $filename = $file;
                    $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                @endphp
                @if (isset($file))
                    <a href="{{ $imageUrl }}" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF Preview" width="100" height="120">
                        <p>View Full PDF</p>
                    </a>
                @endif
                @endforeach
            </div>
            @else
            <input type="file" id="claimintimation" class="file-input" accept="application/pdf">
            <div id="claimintimation-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="claimintimation">Upload Claim Intimation</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Satisfaction Voucher</h2>
            @if(!empty($claimData[0]['satisfaction_voucher_file']))
            @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['satisfaction_voucher_file'])) {
                    $satisfactionVoucherFiles = json_decode($claimData[0]['satisfaction_voucher_file'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $satisfactionVoucherFiles = explode(',', $claimData[0]['satisfaction_voucher_file']);
                    }
                } else {
                    $satisfactionVoucherFiles = $claimData[0]['satisfaction_voucher_file']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('satisfactionvoucher');
            @endphp
            <div id="satisfactionvoucher-preview" class="preview-container">
                @foreach($satisfactionVoucherFiles as $file)
                @php
                    $filename = $file;
                    $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                @endphp
                @if (isset($file))
                    <a href="{{ $imageUrl }}" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF Preview" width="100" height="120">
                        <p>View Full PDF</p>
                    </a>
                @endif
                @endforeach
            </div>
            @else
            <input type="file" id="satisfactionvoucher" class="file-input" accept="application/pdf">
            <div id="satisfactionvoucher-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="satisfactionvoucher">Upload Satisfaction Voucher</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Final Bill</h2>
            @if(!empty($claimData[0]['final_bill_files']))
            @php
                // Check if it's a JSON string, and decode if necessary
                if (is_string($claimData[0]['final_bill_files'])) {
                    $finalBillFiles = json_decode($claimData[0]['final_bill_files'], true); // Decode JSON string to array
                    // If it's not a valid JSON, use explode for comma-separated string
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $finalBillFiles = explode(',', $claimData[0]['final_bill_files']);
                    }
                } else {
                    $finalBillFiles = $claimData[0]['final_bill_files']; // Already an array
                }
                $claimHash = md5($claimId);
                $folderCode = getFolderCode('finalbill');
            @endphp
            <div id="finalbill-preview" class="preview-container">
                @foreach($finalBillFiles as $file)
                @php
                    $filename = $file;
                    $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                @endphp
                @if (isset($file))
                    <a href="{{ $imageUrl }}" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg" alt="PDF Preview" width="100" height="120">
                        <p>View Full PDF</p>
                    </a>
                @endif
                @endforeach
            </div>
            @else
            <input type="file" id="finalbill" class="file-input" accept="application/pdf">
            <div id="finalbill-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="finalbill">Upload Final Bill</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Payment Receipt</h2>
            @if(!empty($claimData[0]['payment_receipt_files']))
                <!-- Convert JSON string to array if needed -->
                @php
                    // Check if it's a JSON string, and decode if necessary
                    if (is_string($claimData[0]['payment_receipt_files'])) {
                        $paymentReceiptFiles = json_decode($claimData[0]['payment_receipt_files'], true); // Decode JSON string to array
                        // If it's not a valid JSON, use explode for comma-separated string
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $paymentReceiptFiles = explode(',', $claimData[0]['payment_receipt_files']);
                        }
                    } else {
                        $paymentReceiptFiles = $claimData[0]['payment_receipt_files']; // Already an array
                    }
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('paymentreceipt');
                @endphp
                <div id="paymentreceipt-preview" class="preview-container">
                    @foreach($paymentReceiptFiles as $file)
                        @php
                            $filename = $file;
                            $imageUrl = route('secure.image', [$claimHash, $folderCode, 'null', $filename]);
                        @endphp
                        @if (isset($file))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <input type="file" id="paymentreceipt" class="file-input" multiple accept="image/*">
            <div id="paymentreceipt-preview" class="preview-container"></div>
            <button class="upload-btn" data-type="paymentreceipt">Upload Payment Receipt</button>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Capture Photos of Damage Vehicle<span style="color: red;">*</span></h2>
            @if (!empty($claimData[0]['photo_files']))
                <!-- Decode JSON string into an array -->
                @php
                    $photoFiles = json_decode($claimData[0]['photo_files'], true); // Decode JSON string to array
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $photoFiles = []; // Fallback to an empty array on error
                    }
                    $photoFiles = json_decode($claimData[0]['photo_files'], true);
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('vehicle');
                @endphp
                <!-- Display existing photos -->
                <div id="photo-preview" class="preview-container">
                    @foreach ($photoFiles as $file)
                        @php
                            $filename = $file['filename'];
                            $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                        @endphp
                        @if (isset($file['filename']))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <!-- Options for capture or upload -->
            <div class="option-selector1" style="margin:5px 0px;">
                <label>
                    <input type="radio" name="action1" value="capture" id="capture-option1" checked>
                    Capture Image
                </label>
                @if (Auth::check())
                    @if (!empty($user->type === 'operator') || !empty($user->type === 'manager'))
                        <label>
                            <input type="radio" name="action1" value="upload" id="upload-option1">
                            Upload Existing Image
                        </label>
                    @endif
                @endif
            </div>
            <!-- Capture option -->
            <div id="capture-container1" class="action-container">
                <button id="capture-photo" class="upload-btn">Capture Photo</button>
                <button class="upload-btn" data-type="photos" style="display:none;">Upload Photos</button>
            </div>
            <!-- Upload option -->
            <div id="upload-container1" class="action-container" style="display: none;">
                <input type="file" id="photo" class="file-input" multiple accept="image/*">
                <button class="upload-btn" data-type="photos">Upload Photos</button>
            </div>
            <!-- Photo preview for new uploads -->
            <div id="photo-preview" class="preview-container"></div>
            @endif
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>Under Repair Photos<span style="color: red;">*</span></h2>
            @if (!empty($claimData[0]['under_repair_photo_files']))
                <!-- Decode JSON string into an array -->
                @php
                    $underRepairFiles = json_decode($claimData[0]['under_repair_photo_files'], true); // Decode JSON string to array
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $underRepairFiles = []; // Fallback to an empty array on error
                    }
                    $underRepairFiles = json_decode($claimData[0]['under_repair_photo_files'], true);
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('under_repair');
                @endphp
                <!-- Display existing photos -->
                <div id="photo-preview" class="preview-container">
                    @foreach ($underRepairFiles as $file)
                        @php
                            $filename = $file['filename'];
                            $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                        @endphp
                        @if (isset($file['filename']))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <div class="option-selector1" style="margin:5px 0px;">
                <label><input type="radio" name="action2" value="capture" id="capture-option2" checked> Capture Image</label>
                @if (Auth::check() && ($user->type === 'operator' || $user->type === 'manager'))
                    <label><input type="radio" name="action2" value="upload" id="upload-option2"> Upload Existing Image</label>
                @endif
            </div>
            <div id="capture-container2" class="action-container">
                <button class="upload-btn" id="capture-repair-photo">Capture Photo</button>
                <button class="upload-btn" data-type="under_repair" style="display:none;">Upload Photos</button>
            </div>
            <div id="upload-container2" class="action-container" style="display:none;">
                <input type="file" id="repair-photo" class="file-input" multiple accept="image/*">
                <button class="upload-btn" data-type="under_repair">Upload Photos</button>
            </div>
            <div id="repair-photo-preview" class="preview-container"></div>
            @endif
            <div class="progress"><div class="progress-bar"></div></div>
        </div>

        <div class="upload-section">
            <h2>Final Photos<span style="color: red;">*</span></h2>
            @if (!empty($claimData[0]['final_photo_files']))
                <!-- Decode JSON string into an array -->
                @php
                    $finalPhotoFiles = json_decode($claimData[0]['final_photo_files'], true); // Decode JSON string to array
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $finalPhotoFiles = []; // Fallback to an empty array on error
                    }
                    $finalPhotoFiles = json_decode($claimData[0]['final_photo_files'], true);
                    $claimHash = md5($claimId);
                    $folderCode = getFolderCode('final');
                @endphp
                <!-- Display existing photos -->
                <div id="photo-preview" class="preview-container">
                    @foreach ($finalPhotoFiles as $file)
                        @php
                            $filename = $file['filename'];
                            $imageUrl = route('secure.image', [$claimHash, 'PHX', $folderCode , $filename]);
                        @endphp
                        @if (isset($file['filename']))
                            <img src="{{ $imageUrl }}" alt="{{ $filename }}" class="preview-image">
                        @endif
                    @endforeach
                </div>
            @else
            <div class="option-selector1" style="margin:5px 0px;">
                <label><input type="radio" name="action3" value="capture" id="capture-option3" checked> Capture Image</label>
                @if (Auth::check() && ($user->type === 'operator' || $user->type === 'manager'))
                    <label><input type="radio" name="action3" value="upload" id="upload-option3"> Upload Existing Image</label>
                @endif
            </div>
            <div id="capture-container3" class="action-container">
                <button class="upload-btn" id="capture-final-photo">Capture Photo</button>
                <button class="upload-btn" data-type="final" style="display:none;">Upload Photos</button>
            </div>
            <div id="upload-container3" class="action-container" style="display:none;">
                <input type="file" id="final-photo" class="file-input" multiple accept="image/*">
                <button class="upload-btn" data-type="final">Upload Photos</button>
            </div>
            <div id="final-photo-preview" class="preview-container"></div>
            @endif
            <div class="progress"><div class="progress-bar"></div></div>
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
        //customized by tanuja
        document.addEventListener('DOMContentLoaded', function () {
            const voiceInputBtn = document.getElementById('voice-input-btn');
            const textArea = document.getElementById('cause-of-accident');
            const wordCountDiv = document.getElementById('word-count');
            const MAX_WORDS = 150;

            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const recognition = new SpeechRecognition();
                recognition.lang = 'hi-IN'; // Hindi language for recognition
                recognition.interimResults = false;

                voiceInputBtn.addEventListener('click', () => {
                    recognition.start();
                });

                recognition.onresult = async (event) => {
                    const transcript = event.results[0][0].transcript;

                    try {
                        // Translate to English
                        const translatedText = await translateToEnglish(transcript);
                        const currentText = textArea.value;
                        const totalWords = currentText.split(/\s+/).filter(Boolean).length + translatedText.split(/\s+/).filter(Boolean).length;

                        if (totalWords <= MAX_WORDS) {
                            textArea.value = currentText + (currentText ? ' ' : '') + translatedText;
                            wordCountDiv.textContent = `${totalWords} / ${MAX_WORDS} words`;
                        } else {
                            alert("You have reached the maximum word limit!");
                        }
                    } catch (error) {
                        alert("Translation failed. Please try again.");
                    }
                };

                recognition.onerror = (event) => {
                    alert(`Error occurred in speech recognition: ${event.error}`);
                };
            } else {
                voiceInputBtn.style.display = 'none';
                alert('Your browser does not support speech recognition.');
            }

            async function translateToEnglish(text) {
                try {
                    const sourceLang = 'hi'; // Hindi as source language
                    const targetLang = 'en'; // English as target language

                    // Create the API URL for Hindi to English translation
                    const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${sourceLang}|${targetLang}`;

                    // Make the API request
                    const response = await fetch(url);

                    if (response.status !== 200) {
                        throw new Error(`API request failed with status ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.responseData && data.responseData.translatedText) {
                        return data.responseData.translatedText;
                    } else {
                        throw new Error('Translation failed: No translated text found');
                    }
                } catch (error) {
                    console.error('Translation Error:', error.message);
                    alert("Translation failed. Please try again.");
                    throw error;
                }
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            const videoCaptureOption = document.getElementById("capture-option"); // Radio button for capturing video
            const uploadExistingVideo = document.getElementById("upload-option"); // Radio button for uploading video

            const captureContainer = document.getElementById("capture-container"); // Capture video button container
            const uploadContainer = document.getElementById("upload-container"); // Upload video file container
            const videoPreview = document.getElementById("video-preview"); // Video preview container
            const existingVideoInput = document.getElementById("existingVideo"); // File input for existing video

            // Function to toggle display based on selected radio button
            function toggleAction() {
                if (!videoPreview || !captureContainer || !uploadContainer) return;

                videoPreview.innerHTML = ''; // Clear preview
                if (videoCaptureOption && videoCaptureOption.checked) {
                    captureContainer.style.display = "block";
                    uploadContainer.style.display = "none";
                    if (existingVideoInput) {
                        existingVideoInput.value = ''; // Clear file input if switching to capture
                    }
                } else if (uploadExistingVideo && uploadExistingVideo.checked) {
                    captureContainer.style.display = "none";
                    uploadContainer.style.display = "block";
                }
            }

            // Add event listeners to radio buttons, if they exist
            if (videoCaptureOption) {
                videoCaptureOption.addEventListener("change", toggleAction);
            }
            if (uploadExistingVideo) {
                uploadExistingVideo.addEventListener("change", toggleAction);
            }

            // Initialize the correct display state
            toggleAction();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const dlOwnerSelect = document.getElementById('dl_owner');
            const ownerDLSection = document.getElementById('owner-dl-section');
            const otherDLSection = document.getElementById('other-dl-section');

            dlOwnerSelect.addEventListener('change', function () {
                const selectedValue = this.value;

                if (selectedValue === 'owner') {
                    ownerDLSection.style.display = 'block';
                    otherDLSection.style.display = 'none';
                } else if (selectedValue === 'other') {
                    ownerDLSection.style.display = 'block';
                    otherDLSection.style.display = 'block';
                } else {
                    ownerDLSection.style.display = 'none';
                    otherDLSection.style.display = 'none';
                }
            });
        });


        //for image capture selection section
        /*document.addEventListener("DOMContentLoaded", () => {
            const imageCaptureOption = document.getElementById("capture-option1"); // Radio button for capturing image
            const uploadExistingImage = document.getElementById("upload-option1"); // Radio button for uploading image

            const captureContainer = document.getElementById("capture-container1"); // Capture photo button container
            const uploadContainer = document.getElementById("upload-container1"); // Upload photo file container
            const photoPreview = document.getElementById("photo-preview"); // Image preview container
            const existingCaptureImage = document.getElementById("photo"); // File input for uploading images

            // Function to toggle display based on selected radio button
            function toggleAction() {
                if (!photoPreview || !captureContainer || !uploadContainer) return;

                photoPreview.innerHTML = ''; // Clear preview
                if (imageCaptureOption && imageCaptureOption.checked) {
                    captureContainer.style.display = "block";
                    uploadContainer.style.display = "none";
                    if (existingCaptureImage) {
                        existingCaptureImage.value = ''; // Clear file input if switching to capture
                    }
                } else if (uploadExistingImage && uploadExistingImage.checked) {
                    captureContainer.style.display = "none";
                    uploadContainer.style.display = "block";
                }
            }

            // Add event listeners to radio buttons, if they exist
            if (imageCaptureOption) {
                imageCaptureOption.addEventListener("change", toggleAction);
            }
            if (uploadExistingImage) {
                uploadExistingImage.addEventListener("change", toggleAction);
            }

            // Initialize the correct display state
            toggleAction();
        });*/

        document.addEventListener("DOMContentLoaded", () => {
            ['1', '2', '3'].forEach(index => {
                const captureOption = document.getElementById(`capture-option${index}`);
                const uploadOption = document.getElementById(`upload-option${index}`);
                const captureContainer = document.getElementById(`capture-container${index}`);
                const uploadContainer = document.getElementById(`upload-container${index}`);
                const fileInput = document.getElementById(index === '1' ? 'photo' : index === '2' ? 'repair-photo' : 'final-photo');
                const preview = document.getElementById(index === '1' ? 'photo-preview' : index === '2' ? 'repair-photo-preview' : 'final-photo-preview');

                function toggleAction() {
                    if (!preview || !captureContainer || !uploadContainer) return;
                    preview.innerHTML = '';
                    if (captureOption && captureOption.checked) {
                        captureContainer.style.display = "block";
                        uploadContainer.style.display = "none";
                        fileInput.value = '';
                    } else if (uploadOption && uploadOption.checked) {
                        captureContainer.style.display = "none";
                        uploadContainer.style.display = "block";
                    }
                }

                captureOption?.addEventListener("change", toggleAction);
                uploadOption?.addEventListener("change", toggleAction);
                toggleAction();
            });

            // Capture button logic for all three sections
            const captureHandlers = {
                "capture-photo": () => alert("Damage vehicle capture clicked"),
                "capture-repair-photo": () => alert("Under repair capture clicked"),
                "capture-final-photo": () => alert("Final photo capture clicked")
            };

            Object.keys(captureHandlers).forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.addEventListener("click", (e) => {
                        e.preventDefault();
                        captureHandlers[id]();
                        // You can replace alert with actual camera logic
                    });
                }
            });
        });


        $('#rcbook-select').on('change', function() {
            const selectedOption = $(this).val(); // Get the selected option
            const $rcbookUploadSection = $('#rcbook-upload-section'); // RC Book upload section
            const $alternativeDocumentsSection = $('#alternative-documents'); // Alternative documents section

            if (selectedOption === 'yes') {
                $rcbookUploadSection.show(); // Show RC Book upload section
                $alternativeDocumentsSection.hide(); // Hide alternative documents section
                $('#rcbook').prop('required', true); // Make RC Book input mandatory
                $('.upload-btn[data-type="rcbook"]').prop('disabled', false); // Enable RC Book upload button
            } else if (selectedOption === 'no') {
                $rcbookUploadSection.hide(); // Hide RC Book upload section
                $alternativeDocumentsSection.show(); // Show alternative documents section
                $('#rcbook').prop('required', false); // Make RC Book input not mandatory
                $('.upload-btn[data-type="rcbook"]').prop('disabled', true); // Disable RC Book upload button
            } else {
                $rcbookUploadSection.hide(); // Hide both sections if no valid option is selected
                $alternativeDocumentsSection.hide();
                $('#rcbook').prop('required', false); // Ensure RC Book input is not mandatory
                $('.upload-btn[data-type="rcbook"]').prop('disabled', true); // Disable upload button
            }
        });

        //end of customized by tanuja code

        $(document).ready(function() {
            const uploadDocumentUrl = "{{ route('upload.document') }}";
            let uploadedDocuments = {
                aadhaar: false,
                pan_card: false,
                rcbook: false,
                tax_receipt: false,
                sales_invoice: false,
                dl: false,
                other_dl: false,
                insurance: false,
                photos: false,
                video: false,
                causeOfAccident: false,
                claimform: false,
                claimintimation: false,
                satisfactionvoucher: false,
                finalbill: false,
                paymentreceipt: false,
                number_plate: false,
            };


            // Function to check if all documents are uploaded
            function allDocumentsUploaded() {
                const allUploaded = Object.values(uploadedDocuments).every(value => value === true);

                // Log each document's upload status
                Object.keys(uploadedDocuments).forEach(documentType => {
                    console.log(`${documentType} uploaded: ${uploadedDocuments[documentType]}`);
                });

                console.log('All documents uploaded:', allUploaded); // Log the final status

                return allUploaded;
            }

            // Function to show the final 'Thank you' message
            function showThankYouMessage() {
                console.log('All documents successfully uploaded. Showing thank you message.');
                $('.container').html('<h2 class="text-center">Thank you for submitting your documents!</h2>');
            }
            // Handle vehicle number submission
            $('#upload-vehicle-number').on('click', function() {
                const vehicleNumber = $('#vehicle-number').val().trim();
                if (!vehicleNumber) {
                    alert('Please enter a vehicle number.');
                    return;
                }

                $('#vehicle-preview').text(`Vehicle Number: ${vehicleNumber}`);
                submitVehicleNumber(vehicleNumber);
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

            // Function to submit vehicle number
            function submitVehicleNumber(vehicleNumber) {
                const claimId = $('#claim-id').val();
                axios.post(uploadDocumentUrl, {
                        claim_id: claimId,
                        vehicle_number: vehicleNumber,
                        document_type: 'vehicle_number'
                    })
                    .then(function(response) {
                        alert('Vehicle number submitted successfully!');
                    })
                    .catch(function(error) {
                        alert('Error submitting vehicle number. Please try again.');
                    });
            }

            // Word count functionality for Cause of Accident
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

            // Submit Cause of Accident
            $('#submit-cause-of-accident').on('click', function() {
                const causeOfAccident = $('#cause-of-accident').val().trim();
                if (!causeOfAccident) {
                    alert('Please enter the cause of accident.');
                    return;
                }

                submitCauseOfAccident(causeOfAccident);
            });

            // Function to submit cause of accident
            function submitCauseOfAccident(causeOfAccident) {
                const claimId = $('#claim-id').val();
                axios.post(uploadDocumentUrl, {
                        claim_id: claimId,
                        cause_of_accident: causeOfAccident,
                        document_type: 'cause_of_accident'
                    })
                    .then(function(response) {
                        alert('Cause of accident submitted successfully!');
                        uploadedDocuments.causeOfAccident = true;
                        if (allDocumentsUploaded()) {
                            showThankYouMessage();
                        }
                    })
                    .catch(function(error) {
                        alert('Error submitting cause of accident. Please try again.');
                    });
            }
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

            // File upload functionality
            $('.upload-btn').on('click', function() {
                const documentType = $(this).data('type');
                const fileInput = $(`#${documentType}`)[0];
                const button = $(this);

                if (fileInput && fileInput.files.length > 0) {
                    uploadFiles(fileInput.files, documentType, button);
                }
            });

            // Function to upload files
            function uploadFiles(files, documentType, button) {
                const formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                }
                formData.append('claim_id', $('#claim-id').val());
                formData.append('document_type', documentType);

                const progressBar = button.siblings('.progress').find('.progress-bar');
                progressBar.parent().show();
                button.attr('disabled', true);

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
                    progressBar.parent().hide();
                    console.log(`${documentType} uploaded successfully`);
                    alert(`${documentType} uploaded successfully!!!`);
                    uploadedDocuments[documentType] = true;

                    if (allDocumentsUploaded()) {
                        showThankYouMessage();
                    }
                }).catch(function(error) {
                    progressBar.parent().hide();
                    button.attr('disabled', false);
                });
            }

            // Photo capture functionality
            let photoCount = 0;
            // const maxPhotos = 20;

            const sectionPhotoCounts = {
                photos: 0,
                under_repair: 0,
                final: 0
            };
            const maxPhotos = 20;

            $('#capture-photo').on('click', function() {
                if (photoCount < maxPhotos) {
                    captureMedia('image');
                }
            });
            $('#capture-repair-photo').on('click', function() {
                if (photoCount < maxPhotos) {
                    captureMedia('under_repair');
                }
            });
            $('#capture-final-photo').on('click', function() {
                if (photoCount < maxPhotos) {
                    captureMedia('final');
                }
            });

            // Video capture functionality
            $('#capture-video').on('click', function() {
                captureMedia('video');
            });


            // Add this function to get geolocation
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
            // Modify the capturePhoto function
            async function capturePhoto(videoElement, stream, section) {
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

                // const previewItem = createPhotoPreview(photoData);
                // $('#photo-preview').append(previewItem);

                const previewItem = createPhotoPreview(photoData);

                // Append to section-specific preview area
                if (section === 'photos') {
                    $('#photo-preview').append(previewItem);
                } else if (section === 'under_repair') {
                    $('#repair-photo-preview').append(previewItem);
                } else if (section === 'final') {
                    $('#final-photo-preview').append(previewItem);
                }
                
                stream.getTracks().forEach(track => track.stop());
                document.getElementById('camera-modal').style.display = 'none';

                 // Increment the photo count for this section
                sectionPhotoCounts[section]++;
                if (sectionPhotoCounts[section] >= maxPhotos) {
                    // Disable only that section's capture button
                    if (section === 'photos') $('#capture-photo').prop('disabled', true);
                    else if (section === 'under_repair') $('#capture-repair-photo').prop('disabled', true);
                    else if (section === 'final') $('#capture-final-photo').prop('disabled', true);
                }

                // Show only relevant upload button
                $('.upload-btn[data-type="' + section + '"]').show();
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
            let mediaRecorder;
            let recordedChunks = [];

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
            let currentFacingMode = 'environment';
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
                                capturePhoto(videoElement, stream,'photos');
                            };
                        } else if (mediaType === 'under_repair') {
                            captureButton.textContent = 'Capture Photo';
                            captureButton.onclick = function() {
                                capturePhoto(videoElement, stream, 'under_repair');
                            };
                        } else if (mediaType === 'final') {
                            captureButton.textContent = 'Capture Photo';
                            captureButton.onclick = function() {
                                capturePhoto(videoElement, stream,'final');
                            };
                        } else if (mediaType === 'number_plate') {
                            captureButton.textContent = 'Capture Photo';
                            captureButton.onclick = function() {
                                captureNumberPlate(videoElement, stream);
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
            // Define globally
            function stampImage(imageDataUrl, timestamp) {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');

                        // Draw original image
                        ctx.drawImage(img, 0, 0);

                        // Format timestamp
                        const dt = new Date(timestamp);
                        const text = [
                            String(dt.getDate()).padStart(2, '0'),
                            String(dt.getMonth() + 1).padStart(2, '0'),
                            dt.getFullYear()
                        ].join('/') + ' ' + [
                            String(dt.getHours() % 12 || 12),
                            String(dt.getMinutes()).padStart(2, '0'),
                            String(dt.getSeconds()).padStart(2, '0')
                        ].join(':');

                        // Style text
                        const fontSize = Math.round(canvas.height * 0.05);
                        ctx.font = `bold ${fontSize}px Arial`;
                        ctx.textBaseline = 'bottom';
                        ctx.textAlign = 'right';

                        const padding = Math.round(fontSize * 0.3);
                        const textWidth = ctx.measureText(text).width;
                        const boxWidth = textWidth + padding * 2;
                        const boxHeight = fontSize + padding * 2;
                        const x = canvas.width - boxWidth - padding;
                        const y = canvas.height - boxHeight - padding;

                        ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                        ctx.fillRect(x, y, boxWidth, boxHeight);

                        ctx.fillStyle = 'white';
                        ctx.fillText(text, canvas.width - padding, canvas.height - padding);

                        canvas.toBlob(blob => {
                            if (blob) resolve(blob);
                            else reject(new Error('Canvas toBlob failed'));
                        }, 'image/jpeg', 0.95);
                    };
                    img.onerror = reject;
                    img.src = imageDataUrl;
                });
            }

            $('.upload-btn[data-type]').on('click', function () {
                const type = $(this).data('type'); // photos | under_repair | final
                const previewSelector = {
                    photos: '#photo-preview',
                    under_repair: '#repair-photo-preview',
                    final: '#final-photo-preview'
                };
                const inputSelector = {
                    photos: 'photo',
                    under_repair: 'repair-photo',
                    final: 'final-photo'
                };

                const previewItems = $(previewSelector[type] + ' .preview-item').toArray();
                const formData = new FormData();

                // Process captured previews
                const capturePromises = previewItems.map((item, idx) => {
                    const photoData = $(item).data('photoData');
                    if (!photoData || !photoData.imageDataUrl) return Promise.resolve();
                    const { imageDataUrl, captureTime, geotag } = photoData;

                    return stampImage(imageDataUrl, captureTime).then(watermarkedBlob => {
                        formData.append('files[]', watermarkedBlob, `photo_${idx + 1}.jpg`);
                        formData.append(`geotags[${idx}]`, JSON.stringify(geotag));
                        formData.append(`captureTimes[${idx}]`, captureTime);
                    });
                });

                // Process uploaded files
                const fileInput = document.getElementById(inputSelector[type]);
                const uploadedFiles = fileInput?.files ? Array.from(fileInput.files) : [];

                const uploadPromises = uploadedFiles.map((file, i) => {
                    const timestamp = new Date().toISOString();
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => {
                            stampImage(reader.result, timestamp)
                                .then(watermarkedBlob => {
                                    const idx = previewItems.length + i;
                                    formData.append('files[]', watermarkedBlob, `photo_${idx + 1}.jpg`);
                                    formData.append(`geotags[${idx}]`, 'null');
                                    formData.append(`captureTimes[${idx}]`, timestamp);
                                    resolve();
                                }).catch(reject);
                        };
                        reader.onerror = reject;
                        reader.readAsDataURL(file);
                    });
                });

                // Final upload
                Promise.all([...capturePromises, ...uploadPromises])
                    .then(() => {
                        uploadCapturedMedia(formData, type);
                    })
                    .catch(err => {
                        console.error('Image processing error:', err);
                        alert('Failed to prepare images for upload.');
                    });
            });

            $('.upload-btn[data-type="video"]').on('click', function() {
                // Get the selected file from input field
                const fileInput = document.getElementById("existingVideo");
                const captureVideo = document.getElementById("capture-video");
                if(captureVideo && recordedChunks.length > 0){
                    const formData = new FormData();
                    const videoBlob = new Blob(recordedChunks, {
                        type: 'video/mp4'
                    });
                    formData.append('files[]', videoBlob, 'captured_video.mp4');
                    // Call upload function
                    uploadCapturedMedia(formData, 'video');
                }else{
                    const file = fileInput.files[0];
                    // Create FormData and append the video file
                    const formData = new FormData();
                    formData.append('files[]', file, 'captured_video.mp4');

                    // Call upload function
                    uploadCapturedMedia(formData, "video");
                }
               
            });

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
                    progressBar.parent().hide();
                    if (type === 'photos') {
                        $('#photo-preview').html('');
                        photoCount = 0;
                        $('#capture-photo').prop('disabled', false);
                    } else if(type === 'under_repair') {
                         $('#repair-photo-preview').html('');
                        photoCount = 0;
                        $('#capture-repair-photo').prop('disabled', false);
                    } else if(type === 'final') {
                         $('#final-photo-preview').html('');
                        photoCount = 0;
                        $('#capture-final-photo').prop('disabled', false);
                    }else if (type === 'video') {
                        $('#video-preview').html('');
                        recordedChunks = [];
                    } else if (type === 'number_plate') {
                        const vehicleNumber = response.data.vehicleNumber;
                        if (vehicleNumber) {
                            $('#vehicle-number').val(vehicleNumber); // Set the value of the input field
                        }
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

            $('#capture-number-plate').on('click', function() {
                captureMedia('number_plate');
            });
            async function captureNumberPlate(videoElement, stream) {
                // Create a canvas element
                const canvas = document.createElement('canvas');
                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;

                // Draw the current frame of the video on the canvas
                canvas.getContext('2d').drawImage(videoElement, 0, 0);

                // Convert the canvas to a data URL (JPEG format)
                const imageDataUrl = canvas.toDataURL('image/jpeg');

                // Get geolocation data
                const geolocation = await getGeolocation();

                // Get current date and time
                const captureTime = new Date().toISOString();

                // Prepare the data object
                const photoData = {
                    imageDataUrl: imageDataUrl,
                    geotag: geolocation,
                    captureTime: captureTime
                };

                // Create a preview item (you can customize this function to display the image)
                const previewItem = createPhotoPreview(photoData);
                $('#number-plate-preview').append(previewItem);

                // Stop the video stream tracks
                stream.getTracks().forEach(track => track.stop());

                // Hide the camera modal if applicable (adjust according to your UI)
                document.getElementById('camera-modal').style.display = 'none';

                // Disable the capture button after the first photo is taken
                $('#capture-number-plate').prop('disabled', true);

                // Show the upload button once the photo is captured
                $('.upload-btn[data-type="number_plate"]').show();
            }
            // Event listener for the upload button (Number Plate)
            $('.upload-btn[data-type="number_plate"]').on('click', function() {
                const formData = new FormData();

                // Get the captured number plate preview item
                const photoData = $('#number-plate-preview .preview-item').data('photoData');

                // Convert image data URL to Blob for upload
                fetch(photoData.imageDataUrl)
                    .then(res => res.blob())
                    .then(blob => {
                        formData.append('files', blob, 'number_plate.jpg');
                        formData.append('geotag', JSON.stringify(photoData.geotag));
                        formData.append('captureTime', photoData.captureTime);

                        // Perform the upload
                        uploadCapturedMedia(formData, 'number_plate');
                    });
            });
        });
    </script>
</body>

</html>
