<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
    
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

    <table style="width: 100%; border-collapse: collapse; font-family: Arial; font-size: 13px;" border="1">
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid black;">
                PRIVATE AND CONFIDENTIAL<br>
                MOTOR FINAL SURVEY REPORT
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding: 5px;">
                This report is issued by me/us as licensed Surveyor(s) without prejudice, in respect of cause nature and extent of loss/damage and subject to the terms and conditions of the insurance policy.
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 18px; font-weight: bold;">
                MOTOR LOSS ASSESSMENT REPORT (FINAL)
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Report No</td>
            <td>SF/UI/2025-26/00</td>
            <td></td>
            <td></td>
            <td style="font-weight: bold;">Date :</td>
            <td>07/04/2025</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Insurer :-</td>
            <td colspan="5">UNITED INDIA INSURANCE CO. LTD.</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Policy No.</td>
            <td> {{ $claim->policy_number }} </td>
            <td style="font-weight: bold;">Validity :</td>
            <td>{{ \Carbon\Carbon::parse($insuranceDetail->insurance_start_date)->format('Y-m-d') }}
                TO
                {{ \Carbon\Carbon::parse($insuranceDetail->insurance_expiry_date)->format('Y-m-d') }}
            </td>
            <td style="font-weight: bold;">IDV :</td>
            <td>560,000.00</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">IMT Endt.</td>
            <td>10,22,28</td>
            <td style="font-weight: bold;">Claim No.</td>
            <td>{{ $claim->claim_id }}</td>
            <td style="font-weight: bold;">PACKAGE POLICY</td>
            <td>{{ $insuranceDetail->policy_type }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Insured :-</td>
            <td colspan="5">M/S REGISTRAR R.E. APPELLATE TRIBUNAL PUNJAB, REAL ESTATE APPELATE TRIBUNAL PUNJAB</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Address</td>
            <td colspan="4">{{ $insuranceDetail->insured_address }}</td>
            <td style="font-weight: bold;">M.NO. {{ $insuranceDetail->mobile }}</td>
        </tr>
    </table>

    @if (!empty($vehicleRegistrationData) && !empty($drivingLicenseData))
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- VEHICLE PARTICULARS -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                VEHICLE PARTICULARS (Online Checked and Verified)
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 5px;">(i) Registered No.</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->rc_number ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(vii) Fitness Certificate No.</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(ii) Date of Registration</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->registration_date->format('Y-m-d') ?? 'NA' }}</td>
            <td style="border: 1px solid black;">Valid upto</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->fit_up_to ? $vehicleRegistrationData->fit_up_to->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iii) Chassis No.</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->vehicle_chasi_number ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(viii) Permit No.</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iv) Engine No.</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->vehicle_engine_number ?? 'NA' }}</td>
            <td style="border: 1px solid black;">Valid upto</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(v) Make</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->maker_model ?? 'NA' }}</td>
            <td style="border: 1px solid black;">Type of Permit</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(vi) Type of Body</td>
            <td style="border: 1px solid black;">{{ $vehicleRegistrationData->body_type ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(ix) Passenger Carrying Capacity</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(vii) Class of Vehicle (Use)</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->vehicle_class ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(xi) Tax paid upto</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
    </table>
    @else
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2"><strong>No vehicle registrations found for this claim.</strong></td>
        </tr>
    </table>
    @endif
    @if (!empty($drivingLicenseData))
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                DRIVER PARTICULARS (Online Checked and Verified)
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(i) Name of the Driver</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->name ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(vi) Issuing Authority</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(ii) Motor Driving License No.</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->license_number ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(vii) Type of License</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->license_type }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iii) Date of Issue</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->issue_date ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(viii) Badge No.</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iv) Valid up to</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->validity_date ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(viii) Endorsement of License</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;"></td>
            <td style="border: 1px solid black;"></td>
            <td style="border: 1px solid black;">(x) Driver Date of Birth</td>
            <td style="border: 1px solid black;">{{ $drivingLicenseData->dob ?? 'NA' }}</td>
        </tr>
    </table>
    @else
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2"><strong>No Driving License Data found for this claim.</strong></td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- ACCIDENT PARTICULARS -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                ACCIDENT PARTICULARS
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(i) Date And Time of Accident</td>
            <td style="border: 1px solid black;">{{ $claim->loss_date ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(iv) Date of deputation</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(ii) Place of Accident</td>
            <td style="border: 1px solid black;">{{ $claim->location ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(v) Date And Time of Inspection</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iii) Place of survey </td>
            <td style="border: 1px solid black;">{{ $claim->place_of_survey ? $claim->place_of_survey : 'N/A'}}</td>
            <td style="border: 1px solid black;">(vi) Additional Survey dates</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;"></td>
            <td style="border: 1px solid black;"></td>
            <td style="border: 1px solid black;">(vii) Spot Survey </td>
            <td style="border: 1px solid black;">Not Conducted</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(iv) Estimate Date</td>
            <td style="border: 1px solid black;">{{ $claim->date ?? 'NA' }}</td>
            <td style="border: 1px solid black;">(viii) Estimate Amount</td>
            <td style="border: 1px solid black;">NA</td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- POLICE REPORT -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                POLICE REPORT
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(i) Whether reported to the Police</td>
            <td style="border: 1px solid black;">No</td>
            <td style="border: 1px solid black;">(ii) Station Diary Entry No.</td>
            <td style="border: 1px solid black;">Not Applicable</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(ii) Name of the Police Station</td>
            <td style="border: 1px solid black;">Not Applicable</td>
            <td style="border: 1px solid black;">(iv) Panchanama carried out</td>
            <td style="border: 1px solid black;">Not Applicable</td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- THIRD PARTY INJURY -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                THIRD PARTY INJURY / THIRD PARTY PROPERTY DAMAGES
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black;">
                There was no T.P. injury or death or property damage as a result of the accident
            </td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- CAUSE OF ACCIDENT -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; font-size: 14px;">
                CAUSE OF ACCIDENT
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; ">
                {{ strtoupper($claim->cause_of_accident) ?? 'NA'}}
            </td>
        </tr>
    </table>
    @php
        $path = public_path('storage/logo/signature1.png');
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
