<!DOCTYPE html>
<html>

<head>
    <title>Motor Survey Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
            font-size: 12px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            z-index: 1000;
        }

        .page-header .logo {
            max-width: 100px;
        }

        .page-header h2 {
            margin: 0;
        }

        /* Adjust content to start below fixed header */
        .content-wrapper {
            margin-top: 150px;
            /* Adjust based on header height */
            padding-bottom: 120px;
            /* Space for footer */
        }

        .ref-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .detail-section {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
        }

        .section-header {
            background-color: #f0f0f0;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            padding: 15px;
            gap: 12px;
        }

        .detail-item {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 10px;
            align-items: center;
        }

        .detail-label {
            font-weight: 600;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .remarks-list {
            margin: 15px;
            padding-left: 20px;
        }

        .remarks-list li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            margin-top: 30px;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ccc;
            font-size: 12px;
            background-color: white;
            z-index: 1000;
            height: auto;
            /* Allow height to adjust to content */
            min-height: 80px;
            /* Minimum height to ensure consistent spacing */
        }

        .company-info {
            margin-bottom: 10px;
        }

        .company-info strong {
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        @media print {
            body {
                padding: 0;
                margin: 20px;
            }

            .page-header,
            .footer {
                position: static;
                background: none;
            }

            .content-wrapper {
                margin-top: 0;
                padding-bottom: 100px;
            }

            table {
                page-break-inside: avoid;
            }

            .detail-section {
                page-break-inside: avoid;
            }

            .footer {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: auto;
                min-height: 80px;
                border-top: 1px solid #ccc;
                background: white;
                z-index: 1000;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- Page Header -->
    <div class="page-header">
        <img src="https://mv-vas.net/storage/upload/logo//united.png" alt="Logo 1" class="logo" width="70%">
        <img src="https://mv-vas.net/storage/upload/logo//logo.png" alt="Logo 2 mt-9" class="logo" width="10%">
        <h2>Motor (Final) Survey Report</h2>
        <span>This report is issued without prejudice, in respect of cause, nature and extent of loss / damage and
            subject to the terms and conditions of the insurance policy & insurer admitting liability.</span>
    </div>
    <div class="content-wrapper">

        <!-- Reference Information -->
        <div class="ref-info">
            <div class="ref-item">
                <strong>Claim No.:</strong> {{ $claim->claim_id }}
            </div>
            <div class="ref-item">
                <strong>Date</strong> {{ date('d-m-Y', strtotime($claim->date)) }}
            </div>
        </div>
        <!-- Professional Fee Details -->
        <div class="detail-section">
            <div class="section-header">Surveyor Details</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">(a) Professional Fee on Estimate of Rs.:</span>
                    <span>{{ $feesBillData->professional_fee ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(b) Reinspection Charges:</span>
                    <span>{{ $feesBillData->reinspection_fee ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(c) Date of Visits:</span>
                    <span>{{ $feesBillData->date_of_visits ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Halting Charges (Final + Re-inspection):</span>
                    <span>{{ $feesBillData->halting_charges ?? 'N/A' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Conveyance Charges - Final:</span>
                    <span>Rs. {{ $feesBillData->conveyance_final ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Distance (km):</span>
                    <span>{{ $feesBillData->distance_final ?? '0' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Rate per km:</span>
                    <span>Rs. {{ $feesBillData->rate_per_km_final ?? '0.00' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Conveyance Charges - Re-inspection:</span>
                    <span>Rs. {{ $feesBillData->conveyance_reinspection ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Distance (km):</span>
                    <span>{{ $feesBillData->distance_reinspection ?? '0' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Rate per km:</span>
                    <span>Rs. {{ $feesBillData->rate_per_km_reinspection ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Number of Photos:</span>
                    <span>{{ $feesBillData->photos_count ?? '0' }} nos.</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Rate per Photograph:</span>
                    <span>Rs. {{ $feesBillData->photo_rate ?? '0.00' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Toll Tax:</span>
                    <span>{{ $feesBillData->distance_reinspection ?? '0' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><strong>TOTAL:</strong></span>
                    <span>Rs. {{ $feesBillData->total_amount ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><strong>Add CGST @ 9%:</strong></span>
                    <span>Rs. {{ $feesBillData->cgst ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><strong>Add SGST @ 9%:</strong></span>
                    <span>Rs. {{ $feesBillData->sgst ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><strong>Add IGST @ 18%:</strong></span>
                    <span>Rs. {{ $feesBillData->igst ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><strong>NET TOTAL:</strong></span>
                    <span>Rs. {{ $feesBillData->net_total ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <strong>Rs. {{ ucfirst(convertNumberToWords($feesBillData->net_total ?? 0)) }} Only.</strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="section-header">BANK & OTHER DETAILS (Ahmedabad)</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">NAME OF BANK:</span>
                    <span>{{ $feesBillData->bank_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">BRANCH NAME:</span>
                    <span>{{ $feesBillData->branch_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">BRANCH ADDRESS:</span>
                    <span>{{ $feesBillData->branch_address ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">BANK A/C NUMBER:</span>
                    <span>{{ $feesBillData->account_number ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">IFSC CODE:</span>
                    <span>{{ $feesBillData->ifsc_code ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">MICR CODE:</span>
                    <span>{{ $feesBillData->micr_code ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ID No.:</span>
                    <span>{{ $feesBillData->id_no ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">GSTIN (UIIC):</span>
                    <span>{{ $feesBillData->gstin ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Insurance Details -->
        <div class="detail-section">
            <div class="section-header">Insurance Details</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Policy No.:</span>
                    <span>{{ $latestInsuranceDetails->policy_number ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Insured Dec Value:</span>
                    <span>{{ $latestInsuranceDetails->insured_declared_value ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Period:</span>
                    <span>
                        {{ date('d-M-y', strtotime($latestInsuranceDetails->insurance_start_date ?? '')) ?? 'Not Available' }}
                        to
                        {{ date('d-M-y', strtotime($latestInsuranceDetails->insurance_expiry_date ?? '')) ?? 'Not Available' }}
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Insured Name:</span>
                    <span>{{ $latestInsuranceDetails->insured_name ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Insured Address:</span>
                    <span>{{ $latestInsuranceDetails->insured_address ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Insurer Office Address:</span>
                    <span>{{ $latestInsuranceDetails->issuing_office_address ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Insured Phone:</span>
                    <span>{{ $latestInsuranceDetails->mobile ?? 'Not Available' }}</span>
                </div>
            </div>
        </div>

        <!-- Vehicle Particulars -->
        <div class="detail-section">
            <div class="section-header">Vehicle Particulars</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">(i) Owner Name.:</span>
                    <span>{{ $latestVehicleDetails->owner_name ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(ii) Registered No.:</span>
                    <span>{{ $latestVehicleDetails->rc_number ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(iii) Date of Registration:</span>
                    <span>
                        {{ date('d/m/Y', strtotime($latestVehicleDetails->registration_date ?? '')) ?? 'Not Available' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">(iv) Chassis No.:</span>
                    <span>{{ $latestVehicleDetails->vehicle_chasi_number ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(v) Engine No.:</span>
                    <span>{{ $latestVehicleDetails->vehicle_engine_number ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(vi) Maker/Model Name:</span>
                    <span>{{ $latestVehicleDetails->maker_model ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(vii) Body Type:</span>
                    <span>{{ $latestVehicleDetails->body_type ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(viii) Vechile Category:</span>
                    <span>{{ $latestVehicleDetails->vehicle_category ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(ix) Fitness Upto.:</span>
                    <span>{{ date('d/m/Y', strtotime($latestVehicleDetails->fit_up_to ?? '')) ?? 'Not Available' }}</span>
                </div>
            </div>
        </div>

        <!-- Driver Particulars -->
        <div class="detail-section">
            <div class="section-header">Driver Particulars</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">(i) Name of Driver:</span>
                    <span>{{ $latestDlDetails->name ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(ii) License No.:</span>
                    <span>{{ $latestDlDetails->license_number ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(iii) Date of Issue:</span>
                    <span>{{ $latestDlDetails->issue_date ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(iv) Valid up to:</span>
                    <span>{{ $latestDlDetails->validity_date ?? 'Not Available' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(v) License Type:</span>
                    <span>{{ $latestDlDetails->license_type ?? 'Not Available' }}</span>
                </div>
            </div>
        </div>

        <!-- Accident Particulars -->
        <div class="detail-section">
            <div class="section-header">Accident Particulars</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">(i) Date & Time of Accident:</span>
                    <span>{{ $claim->loss_date }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(ii) Place of Accident:</span>
                    <span>{{ $claim->location }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">(iii) Place of Survey:</span>
                    <span> </span>
                </div>
            </div>
        </div>

        <!-- Cause of Accident -->
        <div class="detail-section">
            <div class="section-header">Cause of Accident</div>
            <div style="padding: 10px;">
                {{ $claim->cause_of_accident }}
            </div>
        </div>
        <!-- Estimate Damage Report -->
        <div class="detail-section">
            <div class="section-header">Estimate Damage Report</div>
            <table>
                <thead>
                    <tr>
                        <th>Part</th>
                        <th>Material</th>
                        <th>Est Cost</th>
                        <th>Assess Cost</th>
                        <th>Tax</th>
                        <th>Tax Amt</th>
                        <th>Total Amt</th>
                        <th>Rate of Dep.</th>
                        <th>Dep.Amt</th>
                        <th>Final Amt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($damageTableResult as $index => $result)
                    <tr data-index="{{ $index }}">
                        <td>{{ $result['partName'] }}</td>
                        <td>{{ $result['material'] ?? '' }}</td>
                        <td>{{ $result['estimateCost'] ?? 0 }}</td>
                        <td>{{ $result['assessedCost'] ?? 0 }}</td>
                        <td>{{ $result['taxPercentage'] ?? 0 }}%</td>
                        <td id="taxAssess">₹{{ $result['taxAmount'] ?? 0 }}</td>
                        <td id="assessAmount">₹{{ $result['totalAmount'] ?? 0 }}</td>
                        <td>{{ $result['depreciationRate'] }}%</td>
                        <td id="DepAmount">₹{{ $result['depreciationAmount'] ?? 0 }}</td>
                        <td id="finalAssessAmount">₹{{ $result['finalAmount'] ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td><strong id="totalEstimateCost">₹{{ collect($damageTableResult)
                            ->map(function ($item) {
                                return (float) $item['estimateCost']; // Convert to float
                            })
                            ->sum() }}</strong></td>
                        <td><strong id="totalAssessedCost">₹{{ collect($damageTableResult)->sum('assessedCost') }}</strong></td>
                        <td></td>
                        <td><strong id="totalAssesTaxAmount">₹{{ collect($damageTableResult)
                            ->map(function ($item) {
                                return (float) $item['taxAmount']; // Convert to float
                            })
                            ->sum() }}</strong></td>
                        <td><strong id="totalAmount">₹{{ collect($damageTableResult)
                            ->map(function ($item) {
                                return (float) $item['totalAmount']; // Convert to float
                            })
                            ->sum() }}</strong></td>
                        <td></td>
                        <td><strong id="totalDepAmount">₹{{ collect($damageTableResult)->sum('depreciationAmount') }}</strong></td>
                        <td><strong id="finalAssessTotalAmount">₹{{ collect($damageTableResult)
                            ->map(function ($item) {
                                return (float) $item['finalAmount']; // Convert to float
                            })
                            ->sum() }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Labour Damage Report -->
        <div class="detail-section">
            <div class="section-header">Estimate Labour Report</div>
            <table>
                <thead>
                    <tr>
                        <th>Desc</th>
                        <th>Estimate Cost</th>
                        <th>Assessed Cost</th>
                        <th>Tax</th>
                        <th>Tax Amount</th>
                        <th>Total Amount</th>
                        <th>Rate of Dep.</th>
                        <th>Dep.Amt</th>
                        <th>Final Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labourTableResult as $index => $result)
                        <tr>
                            <td>{{ $result['descriptionLabour'] ?? '' }}</td>
                            <td>{{ $result['estimateLabourCost'] ?? 0 }}</td>
                            <td>{{ $result['assessedLabourCost'] ?? 0 }}</td>
                            <td>{{ $result['taxLabourPercentage'] ?? 0 }}%</td>
                            <td class="taxLabour">₹{{ $result['taxLabourAmount'] ?? 0 }}</td>
                            <td class="taxLabourAmount">₹{{ $result['totalLabourAmount'] ?? 0 }}</td>
                            <td>0.00%</td>
                            <td>0.00%</td>
                            <td class="finalLabourAmount">₹{{ $result['finalLabourAmount'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td><strong>Total</strong></td>
                        <td><strong id="totalEstimateLabourCost">₹{{ collect($labourTableResult)->sum('estimateLabourCost') }}</strong></td>
                        <td><strong id="totalAssessLabourCost">₹{{ collect($labourTableResult)->sum('assessedLabourCost') }}</strong></td>
                        <td><strong></strong></td>
                        <td><strong id="totalLabourTaxAmount">₹{{ collect($labourTableResult)->sum('taxLabourAmount') }}</strong></td>
                        <td><strong id="totalLabourAmount">₹{{ collect($labourTableResult)->sum('totalLabourAmount') }}</strong></td>
                        <td></td>
                        <td></td>
                        <td><strong id="totalFinalLabourAmount">₹{{ collect($labourTableResult)->sum('finalLabourAmount') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Summary -->
        <div class="detail-section">
            <div class="section-header">Summary</div>
            <table>
                <thead>
                    <tr>
                        <th><strong>Estimate :-</strong></th>
                        <th><strong>Assessed For :-</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Labour Charges: <strong id="totalEstimateLabour1">₹{{ $summaryTableResult['totalEstimateLabour'] ?? 0 }}</strong></td>
                        <td>Total Labour Charges: <strong id="totalAssessedLabourTax">₹{{ $summaryTableResult['totalFinalLabourAmount'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Cost of Parts: <strong id="totalEstimateParts">₹{{ $summaryTableResult['totalEstimateParts'] ?? 0 }}</strong></td>
                        <td>Total Spare Parts: <strong id="totalAssessedParts">₹{{ $summaryTableResult['totalAssessedParts'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Less Excess (-): {{ $summaryTableResult['lessExcess'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong> <strong id="totalEstimate">₹{{ $summaryTableResult['totalEstimate'] ?? 0 }}</strong></td>
                        <td><strong>Total:</strong> <strong id="totalAssessed">₹{{ $summaryTableResult['totalAssessed'] ?? 0 }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Remarks -->
        <div class="detail-section">
            <div class="section-header">Remarks</div>
            <ol class="remarks-list">
                <li>The rates allowed above combination of authorised dealer prices.</li>
                <li>The cause, nature and circumstances leading to accident appears, genuine, believable and losses
                    recommended/assessed are corroborating with this accident.</li>
                <li>The damages as observed were found to be fresh and consistent with the nature of accident as
                    reported.
                </li>
                <li>The R.C. and the D.L. were verified from the original documents and found in order.</li>
                <li>The loss or damage or liability has arisen proximately caused by the insured perils.</li>
                <li>The prices are recommended exclusive of all taxes, duties, octroi etc.</li>
            </ol>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="company-info">
            <strong>SAFETY FIRST INSURANCE SURVEYOR & LOSS ASSESSOR PRIVATE LIMITED</strong><br>
            408, GEETANJALI TOWER,, NEAR CIVIL LINES METRO STATION, AJMER ROAD, JAIPUR - 302006,<br>
            Rajasthan, INDIA
        </div>
    </div>
</body>

</html>
