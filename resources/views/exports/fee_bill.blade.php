<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<br>
    <!-- Header Section -->
    @php
        $path = public_path('storage/logo/safety1logo.png');
        $imageData = base64_encode(file_get_contents($path));
        $src = 'data:'.mime_content_type($path).';base64,'.$imageData;
    @endphp

    @if (!isset($forExcel) || !$forExcel)
        {{-- Only show this for PDF --}}
        <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="1" style="font-size: 45px; font-weight: bold;">
                <img src="{{ $src }}" alt="Safety First Logo" style="width: 150px; height: auto; margin-right: 10px;">   
            </td>
            <td colspan="1" style="font-size: 12px;">
                <b>Off: 0731-4103236</b>
                <br>
                <b>Cell off: +91 98930 55855</b>
                <br>
                <b>+91 97890112494</b>
                <br>
                <b>Toll Free 08062965696 (40 Lines)</b>
                <br>
                <b>E-mail:</b> <span style="color: blue;">info@safetyfirst.co.in, claims@safetyfirst.co.in</span>
                <br>
                <b>Office: 3rd floor Sihansa IT park, Dhar road, Indore (MP)</b>
            </td>
        </tr>
    </table>
    @else
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            {{-- The logo is inserted via Excel Drawing at A1 --}}
            <td style="width: 40%;"></td>
            <td style="text-align: right; font-size: 12px;">
                <strong>Off- 0731-4103236</strong><br>
                <strong>Cell off. +91 98939 55855</strong><br>
                <strong>+91 7880112494</strong><br>
                <strong>Toll Free 08062965696 (40 Lines)</strong><br>
                <strong>E-mail:<span style="color: blue;">info@safetyfirst.co.in, claims@safetyfirst.co.in</span></strong><br>
                <strong>Office - 3rd floor Sihansa IT park, Dhar road, Indore (MP)</strong>
            </td>
        </tr>
    </table>
    @endif
    
    <!-- Client Details -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="font-weight: bold; background-color: #c0c0c0;">Ref. No.: <span style="background-color: #c0c0c0;">SFU/2024-25016</span></td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">DATE: <span style="background-color: #c0c0c0;">{{ $claim->date }}</span></td>
        </tr>
        <tr>
            <td>To,</td>
        </tr>
        <tr>
            <td colspan="1" style="font-weight: bold;">UNITED INDIA INSURANCE COMPANY LTD.</td>
        </tr>
        <tr>
            <td colspan="1" style="font-weight: bold;">CLAIM HUB, KANPUR</td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="font-weight: bold;">Claim No: <span style="background-color: #c0c0c0;">{{ $claim->claim_id }}</span></td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Vehicle No.: {{ $claim->vehicle_number }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Policy/Cover Note No.: {{ $claim->policy_number }}</td>
            <td style="font-weight: bold; background-color: #c0c0c0;">
                Validity: {{ \Carbon\Carbon::parse($insuranceDetail->insurance_start_date)->format('Y-m-d') }}
                TO
                {{ \Carbon\Carbon::parse($insuranceDetail->insurance_expiry_date)->format('Y-m-d') }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Insured Name: {{ $insuranceDetail->insured_name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Insured Address: {{ $insuranceDetail->insured_address }}</td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Phone No.: {{ $insuranceDetail->mobile }}</td>
        </tr>

    </table>

    <!-- Charges Table -->
    <table style="width: 100%; border-collapse: collapse;">
        <!-- Row 1 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">(a) Professional Fee on Estimate of Rs.</td>
            <td style="border: 1px solid black; text-align: right;">{{ $feesBillData->professional_fee ?? '0.00' }}</td>
            <td rowspan="8" style="background-color: #fff8b0; text-align: center; font-weight: bold; font-size: 22px; border: 1px solid black;">{{ $feesBillData->total_amount ?? '0.00' }}</td>
            
        </tr>
        <!-- Row 2 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">(b) Reinspection Charges</td>
            <td style="border: 1px solid black; text-align: right;">{{ $feesBillData->reinspection_fee ?? '0.00' }}</td>
        </tr>
        <!-- Row 3 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">(c) Date of Visits:</td>
            <td style="border: 1px solid black; text-align: right;">{{ $feesBillData->date_of_visits ?? '' }}</td>
        </tr>
        <!-- Row 4 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">Halting Charges (Final + Re-inspection)</td>
            <td style="border: 1px solid black; text-align: right;">{{ $feesBillData->halting_charges ?? '0.00' }}</td>
        </tr>
        <!-- Row 5 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">Conveyance Charges - Final
                <br>
                Total Distance in km: {{ $feesBillData->distance_final ?? '0.00' }}
                <br>
                Rate per km: {{ $feesBillData->rate_per_km_final ?? '0.00' }}
            </td>
            <td colspan="1" style="text-align: right; border: 1px solid black;">{{ $feesBillData->conveyance_final ?? '0.00' }}</td>
        </tr>
        <!-- Row 6 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">Conveyance Charges - Re-inspection
                <br>
                Total Distance in km: {{ $feesBillData->distance_reinspection ?? '0.00' }}
                <br>
                Rate per km: {{ $feesBillData->rate_per_km_reinspection ?? '0.00' }}
            </td>
            <td colspan="1" style="text-align: right; border: 1px solid black;">{{ $feesBillData->conveyance_reinspection ?? '0.00' }}</td>
        </tr>
        <!-- Row 7 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">Photographs - Final + Re-inspection
                <br>    
                Number of Photos: {{ $feesBillData->photos_count ?? '0.00' }}
                <br>
                Rate per Photograph: {{ $feesBillData->photo_rate ?? '0.00' }}
            </td>
            <td colspan="1" style="text-align: right; border: 1px solid black;">0.00</td>
        </tr>
        <!-- Row 8 -->
        <tr>
            <td colspan="1" style="border: 1px solid black;">Toll Tax</td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->toll_tax ?? '0.00' }}</td>
        </tr>
        <!-- Row 9 -->
        <tr>
            <td colspan="2" style="border: 1px solid black; text-align: right; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid black; text-align: right; font-weight: bold; background-color: #d9d9d9;">{{ $feesBillData->total_amount ?? '0.00' }}</td>
        </tr>
        <!-- Row 10 -->
        <tr>
            <td colspan="2" style="border: 1px solid black;">Add CGST @ 9%</td>
            <td style="border: 1px solid black; text-align: right; background-color: #d9d9d9;">{{ $feesBillData->cgst ?? '0.00' }}</td>
        </tr>
        <!-- Row 11 -->
        <tr>
            <td colspan="2" style="border: 1px solid black;">Add SGST @ 9%</td>
            <td style="border: 1px solid black; text-align: right; background-color: #d9d9d9;">{{ $feesBillData->sgst ?? '0.00' }}</td>
        </tr>
        <!-- Row 12 -->
        <tr>
            <td colspan="2" style="border: 1px solid black;">Add IGST @ 18%</td>
            <td style="border: 1px solid black; text-align: right; background-color: #d9d9d9;">{{ $feesBillData->igst ?? '0.00' }}</td>
        </tr>
        <!-- Row 13 -->
        <tr>
            <td colspan="2" style="text-align: right; font-weight: bold; font-size: 16px; border: 1px solid black;">NET TOTAL</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid black; background-color: #d9d9d9;">{{ $feesBillData->net_total ?? '0.00' }}</td>
        </tr>
        <!-- Row 14 -->
        <tr>
            <td colspan="8" style="font-style: italic; font-weight: bold;">Rs. {{ ucfirst(convertNumberToWords($feesBillData->net_total ?? '')) }} Only.</td>
        </tr>
    </table>



    <!-- Bank Details -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">BANK And OTHER DETAILS (Ahmedabad)</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">NAME OF BANK</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->bank_name ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">BRANCH NAME</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->branch_name ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">BRANCH ADDRESS</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->branch_address ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">BANK A/C NUMBER (CORE BANKING)</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->account_number ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">IFSC CODE (11 DIGIT ALPHA-NUMERIC NUMBER)</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->ifsc_code ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">MICR CODE</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->micr_code ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">ID No.</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->id_no ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; font-weight: bold;">GSTIN (UIIC)</td>
            <td style="border: 1px solid black; text-align: left;">{{ $feesBillData->gstin ?? '' }}</td>
        </tr>
    </table>
    @php
        $path = public_path('storage/logo/officialSignature.png');
        $imageData = base64_encode(file_get_contents($path));
        $src = 'data:'.mime_content_type($path).';base64,'.$imageData;
    @endphp

    @if (!isset($forExcel) || !$forExcel)
        {{-- Only show this for PDF --}}
        <div style="text-align: right; margin-top: 20px;">
            <img src="{{ $src }}" alt="Signature" style="height: 80px;">
        </div>
    @endif

</body>
</html>
