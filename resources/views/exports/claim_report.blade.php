<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
    <br>
    <!-- Header Section -->
     <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="1" style="font-size: 28px; font-weight: bold;">
                SAFETY FIRST
            </td>
            <td colspan="1" style="font-size: 12px;">
                <b>Address:</b> 3rd floor, 
                <br>
                Sahinsa IT Park, Dhar road,
            </td>
        </tr>
        <tr>
            <td colspan="1" style="font-size: 12px;">
                Insurance Surveyor And Loss Assessor
            </td>
            <td colspan="1" style="font-size: 12px;">
                <b>Indore, MP</b>
            </td>
        </tr>
        <tr>
            <td colspan="1" style="font-size: 12px;">
                <b>PAN Number:</b> AAUCS4578C
            </td>
            <td colspan="1" style="font-size: 12px;">
                 <b>Ph:</b> 07314103236, 08062965696
            </td>
        </tr>
        <tr>
            <td colspan="1" style="font-size: 12px; font-weight: bold; background-color: yellow;">
                GSTIN: 08AAUCS4758CIZV
            </td>
            <td colspan="1">
                <b>E-mail:</b> <span style="color: blue;">info@safetyfirst.co.in, claims@safetyfirst.co.in</span>
            </td>
        </tr>
    </table>

    <!-- Client Details -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="font-weight: bold; background-color: #c0c0c0;">Bill No.: SFU/2024-25016</td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">DATE: {{ date("d/m/Y") }}</td>
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
        <br>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Claim No: {{ $claim->claim_id }}</td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Insured Prop./Vehicle No.: {{ $claim->vehicle_number }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Policy/Cover Note No.: {{ $claim->policy_number }}</td>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Validity: .... TO .....</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Insured Name:</td>
        </tr>
        <tr>
            <td style="font-weight: bold;  background-color: #c0c0c0;">Insured Address And Phone No.:</td>
        </tr>
    </table>

    <!-- Charges Table -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="border: 1px solid black;">(a) Professional Fee on Estimate of Rs.</td>
            <td style="text-align: right;  background-color: #c0c0c0; border: 1px solid black;">{{ $feesBillData->professional_fee ?? '0.00' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(b) Reinspection Charges</td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->reinspection_fee ?? '0.00' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">(c) Date of Visits:</td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->date_of_visits ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Halting Charges (Final + Re-inspection)</td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->halting_charges ?? '0.00' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Conveyance Charges - Final
                <br>
                Total Distance in km: {{ $feesBillData->distance_final ?? '0.00' }}
                <br>
                Rate per km: {{ $feesBillData->rate_per_km_final ?? '0.00' }}
            </td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->conveyance_final ?? '0.00' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Conveyance Charges - Re-inspection
                <br>
                Total Distance in km: {{ $feesBillData->distance_reinspection ?? '0.00' }}
                <br>
                Rate per km: {{ $feesBillData->rate_per_km_reinspection ?? '0.00' }}
            </td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->conveyance_reinspection ?? '0.00' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Photographs - Final + Re-inspection
                <br>    
                Number of Photos: {{ $feesBillData->photos_count ?? '0.00' }}
                <br>
                Rate per Photograph: {{ $feesBillData->photo_rate ?? '0.00' }}
            </td>
            <td style="text-align: right; border: 1px solid black;">0.00</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;">Toll Tax </td>
            <td style="text-align: right; border: 1px solid black;">{{ $feesBillData->toll_tax ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: right; font-weight: bold;">TOTAL</td>
            <td style="background-color: #c0c0c0; font-weight: bold; text-align: right;">{{ $feesBillData->total_amount ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid black;">Add CGST @ 9%</td>
            <td style="text-align: right;  background-color: #c0c0c0;">{{ $feesBillData->cgst ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid black;">Add SGST @ 9%</td>
            <td style="text-align: right;  background-color: #c0c0c0;">{{ $feesBillData->sgst ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid black;">Add IGST @ 18%</td>
            <td style="text-align: right;  background-color: #c0c0c0;">{{ $feesBillData->igst ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: right; font-weight: bold; font-size: 14px; border: 1px solid black;">NET TOTAL</td>
            <td style="font-weight: bold; text-align: right;  background-color: #c0c0c0;">{{ $feesBillData->net_total ?? '0.00' }}</td>
        </tr>
        <tr>
            <td colspan="4" style="font-weight: bold;"><i>Rs. {{ ucfirst(convertNumberToWords($feesBillData->net_total ?? '')) }} Only.</i></td>
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

    <br>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 12px; font-weight: bold; border: 1px solid black;">PRIVATE AND CONFIDENTIAL</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-size: 12px; font-weight: bold; border: 1px solid black;">MOTOR FINAL SURVEY REPORT</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: bold; border: 1px solid black;">This report is issued by me/us as licensed Surveyor(s) without prejudice, in respect of cause nature and extent of loss/damage and subject to the terms and conditions of the insurance policy.</td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 18px; font-weight: bold;">MOTOR LOSS ASSESSMENT REPORT (FINAL)</td>
        </tr>
    </table>
    @if (!empty($vehicleRegistrationData) && !empty($drivingLicenseData))
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <!-- VEHICLE PARTICULARS -->
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
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
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
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
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
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
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
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
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
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
            <td colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px;">
                CAUSE OF ACCIDENT
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black; padding: 5px;">
                {{ strtoupper($claim->cause_of_accident) ?? 'NA'}}
            </td>
        </tr>
    </table>


    <!-- Header Section ASSESSMENT  -->

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="2" style="text-align: center; font-size: 18px; font-weight: bold;">ASSESSMENT OF LOSS REPORT (FINAL)</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr><th colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px; text-align: left;"> (I) Assessed for the SPARE PARTS of accident vehicle bearing Regd. No. - </th></tr>
            <tr>
                <th style="border: 1px solid black; font-weight: bold;">Part</th>
                <th style="border: 1px solid black; font-weight: bold;">Material</th>
                <th style="border: 1px solid black; font-weight: bold;">Est Cost</th>
                <th style="border: 1px solid black; font-weight: bold;">Assess Cost</th>
                <th style="border: 1px solid black; font-weight: bold;">Tax</th>
                <th style="border: 1px solid black; font-weight: bold;">Tax Amt</th>
                <th style="border: 1px solid black; font-weight: bold;">Total Amt</th>
                <th style="border: 1px solid black; font-weight: bold;">Rate of Dep.</th>
                <th style="border: 1px solid black; font-weight: bold;">Dep.Amt</th>
                <th style="border: 1px solid black; font-weight: bold;">Final Amt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($damageTableResult as $result)
                <tr>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['partName'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['material'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['estimateCost'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['assessedCost'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['taxPercentage'] }}%</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['taxAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['totalAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['depreciationRate'] }}%</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['depreciationAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['finalAmount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr><th colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px; text-align: left;"> (II) LABOUR CHARGES</th></tr>
            <tr>
                <th style="border: 1px solid black; font-weight: bold;">Desc</th>
                <th style="border: 1px solid black; font-weight: bold;">Estimate Cost</th>
                <th style="border: 1px solid black; font-weight: bold;">Assessed Cost</th>
                <th style="border: 1px solid black; font-weight: bold;">Tax</th>
                <th style="border: 1px solid black; font-weight: bold;">Tax Amount</th>
                <th style="border: 1px solid black; font-weight: bold;">Total Amount</th>
                <th style="border: 1px solid black; font-weight: bold;">Rate of Dep.</th>
                <th style="border: 1px solid black; font-weight: bold;">Dep.Amt</th>
                <th style="border: 1px solid black; font-weight: bold;">Final Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labourTableResult as $result)
                <tr>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['descriptionLabour'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['estimateLabourCost'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['assessedLabourCost'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $result['taxLabourPercentage'] }}%</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['taxLabourAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['totalLabourAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: left;">0.00%</td>
                    <td style="border: 1px solid black; text-align: left;">0.00%</td>
                    <td style="border: 1px solid black; text-align: left;">₹{{ $result['finalLabourAmount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr><th colspan="2" style="font-weight: bold; border: 1px solid black; padding: 5px; font-size: 14px; text-align: left;">SUMMARY OF ASSESSMENT</th></tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black;"><strong>Estimate :</strong></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"><strong>Assessed for :</strong></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Total Labour Charges :</td>
                <td style="border: 1px solid black; text-align: left;">{{ $summaryTableResult['totalEstimateLabour'] }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;">Total Labour Charges :</td>
                <td style="border: 1px solid black; text-align: left;">{{ $summaryTableResult['totalFinalLabourAmount'] }}</td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Total Cost of Parts :</td>
                <td style="border: 1px solid black; text-align: left;">{{ $summaryTableResult['totalEstimateParts'] }}</td>
                <td style="border: 1px solid black;"></td>
                <td>Total Spare Parts :</td>
                <td style="border: 1px solid black; text-align: left;">{{ $summaryTableResult['totalAssessedParts'] }}</td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Less Excess (-) :</td>
                <td style="border: 1px solid black; text-align: left;">{{ $summaryTableResult['lessExcess'] ?? 0 }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;"><strong>TOTAL :</strong></td>
                <td style="border: 1px solid black; text-align: left;"><strong>{{ $summaryTableResult['totalEstimate'] }}</strong></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"><strong>TOTAL :</strong></td>
                <td style="border: 1px solid black; text-align: left;"><strong>{{ $summaryTableResult['totalAssessed'] }}</strong></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td colspan="10"><strong>Hence, the Net Loss assessment comes to = INR {{ $summaryTableResult['totalAssessed'] }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- REMARKS SECTION -->
    <table>
        <tr><td colspan="3"><strong>REMARKS</strong></td></tr>
        <tr><td colspan="3">1. The damages as observed were found to be fresh and consistent with the nature of accident as reported.The loss was proximately caused by an insured peril and none of the exclusions under the policy had operated to bring about the loss</td></tr>
        <tr><td colspan="3">2. The R.C. and the D.L. were verified from the original documents and found in order. The photocopies of the same are enclosed with this survey report. 
        Repair Bill Received on Dt.</td></tr>
        <tr><td colspan="3"><strong>3. Payment to Insured.</strong></td></tr>
    </table>

    <table>
        <tr>
            <td colspan="3" style="font-size: 12px; font-weight: bold;">Enclosed please find here with:</td>
        </tr>
        <tr><td colspan="3">1. Digital Photographs 08 nos.</td></tr>
        <tr><td colspan="3">2. Loss Assessment Fee bill in duplicate.</td></tr>
        <tr><td colspan="3">3. Claim form and Estimate.</td></tr>
        <tr><td colspan="3">4. Copies of the vehicular documents duly verified from original as produced.</td></tr>
        <tr><td colspan="3">5. Repair Invoice.</td></tr>
    </table>

</body>
</html>
