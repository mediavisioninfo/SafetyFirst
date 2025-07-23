<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

    <!-- Header Section -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="1" style="font-weight: bold; text-align: left; font-size: 14px;">
                SAFETY FIRST
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="1" style="font-weight: bold; text-align: left; font-size: 14px;">
                 BILL DATE:  {{ $claim->date }}
            </td>
            <td colspan="1" style="font-weight: bold; text-align: left; font-size: 14px;">
                BILL AMOUNT:
            </td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: left; font-size: 12px;">
                Insurance Surveyor and Loss Assessor
            </td>
        </tr>
    </table>
    <!-- Header Section ASSESSMENT  -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="11" style="font-weight: bold; text-align: left; font-size: 14px;">
                ASSESSMENT OF LOSS
            </td>
        </tr>
        <tr>
            <td colspan="11" style="font-weight: bold; text-align: left; font-size: 14px;">
                (I) Assessed for the <span style="font-weight: bold; text-decoration: underline;"> SPARE PARTS </span> of accident vehicle bearing Regd. No. -
            </td>
        </tr>
        <tr>
            <th style="border: 1px solid black; font-weight: bold;">Part</th>
            <th style="border: 1px solid black; font-weight: bold;">Material</th>
            <th style="border: 1px solid black; font-weight: bold;">Estimate cost in INR</th>
            <th style="border: 1px solid black; font-weight: bold;">Assessed for in INR</th>
            <th style="border: 1px solid black; font-weight: bold;">Tax % (GST)</th>
            <th style="border: 1px solid black; font-weight: bold;">Tax Amount in INR</th>
            <th style="border: 1px solid black; font-weight: bold;">Total Amount in INR</th>
            <th style="border: 1px solid black; font-weight: bold;">Rate of Dep.</th>
            <th style="border: 1px solid black; font-weight: bold;">Dep. amount</th>
            <th style="border: 1px solid black; font-weight: bold;">Final Amount in INR</th>
        </tr>
        @foreach ($damageTableResult as $result)
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $result['partName'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['material'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['estimateCost'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['assessedCost'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['taxPercentage'] }}%</td>
                <td style="border: 1px solid black; text-align: center;"><span style="font-family: DejaVu Sans;"></span>{{ $result['taxAmount'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['totalAmount'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['depreciationRate'] }}%</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['depreciationAmount'] }}</td>
                <td style="border: 1px solid black; text-align: center;">{{ $result['finalAmount'] }}</td>
            </tr>
        @endforeach
            <tr class="font-weight-bold">
                <td style="border: 1px solid black; text-align: center;"><strong>Total</strong></td>
                <td style="border: 1px solid black; text-align: center;"></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="totalEstimateCost">{{ collect($damageTableResult)
                    ->map(function ($item) {
                        return (float) $item['estimateCost']; // Convert to float
                    })
                    ->sum() }}</strong></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="totalAssessedCost">{{ collect($damageTableResult)->sum('assessedCost') }}</strong></td>
                <td style="border: 1px solid black; text-align: center;"></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="totalAssesTaxAmount">{{ collect($damageTableResult)
                    ->map(function ($item) {
                        return (float) $item['taxAmount']; // Convert to float
                    })
                    ->sum() }}</strong></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="totalAmount">{{ collect($damageTableResult)
                    ->map(function ($item) {
                        return (float) $item['totalAmount']; // Convert to float
                    })
                    ->sum() }}</strong></td>
                <td style="border: 1px solid black; text-align: center;"></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="totalDepAmount">{{ collect($damageTableResult)->sum('depreciationAmount') }}</strong></td>
                <td style="border: 1px solid black; text-align: center;"><strong id="finalAssessTotalAmount">{{ collect($damageTableResult)
                    ->map(function ($item) {
                        return (float) $item['finalAmount']; // Convert to float
                    })
                    ->sum() }}</strong></td>
            </tr>
    </table>
    <br>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr><th colspan="11" style="font-weight: bold; text-align: left; font-size: 14px;"> (II) LABOUR CHARGES</th></tr>
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
                    <td style="border: 1px solid black; text-align: center;">{{ $result['descriptionLabour'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['estimateLabourCost'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['assessedLabourCost'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['taxLabourPercentage'] }}%</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['taxLabourAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['totalLabourAmount'] }}</td>
                    <td style="border: 1px solid black; text-align: center;">0.00%</td>
                    <td style="border: 1px solid black; text-align: center;">0.00%</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $result['finalLabourAmount'] }}</td>
                </tr>
            @endforeach
                <tr class="font-weight-bold">
                    <td style="border: 1px solid black; text-align: center;"><strong>Total</strong></td>
                    <td style="border: 1px solid black; text-align: center;"><strong id="totalEstimateLabourCost">{{ collect($labourTableResult)->sum('estimateLabourCost') }}</strong></td>
                    <td style="border: 1px solid black; text-align: center;"><strong id="totalAssessLabourCost">{{ collect($labourTableResult)->sum('assessedLabourCost') }}</strong></td>
                    <td style="border: 1px solid black; text-align: center;"><strong></strong></td>
                    <td style="border: 1px solid black; text-align: center;"><strong id="totalLabourTaxAmount">{{ collect($labourTableResult)->sum('taxLabourAmount') }}</strong></td>
                    <td style="border: 1px solid black; text-align: center;"><strong id="totalLabourAmount">{{ collect($labourTableResult)->sum('totalLabourAmount') }}</strong></td>
                    <td style="border: 1px solid black; text-align: center;"></td>
                    <td style="border: 1px solid black; text-align: center;"></td>
                    <td style="border: 1px solid black; text-align: center;"><strong id="totalFinalLabourAmount">{{ collect($labourTableResult)->sum('finalLabourAmount') }}</strong></td>
                </tr>
        </tbody>
    </table>

    <br>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr>
                <th colspan="6" style="font-weight: bold; border: 1px solid black; font-size: 14px; text-align: left;">
                    SUMMARY OF ASSESSMENT
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; font-weight: bold;">Estimate :</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black; font-weight: bold;">Assessed for :</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Total Labour Charges :</td>
                <td style="border: 1px solid black; text-align: right;">{{ $summaryTableResult['totalEstimateLabour'] }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;">Total Labour Charges :</td>
                <td style="border: 1px solid black; text-align: right;">{{ $summaryTableResult['totalFinalLabourAmount'] }}</td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Total Cost of Parts :</td>
                <td style="border: 1px solid black; text-align: right;">{{ $summaryTableResult['totalEstimateParts'] }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;">Total Spare Parts :</td>
                <td style="border: 1px solid black; text-align: right;">{{ $summaryTableResult['totalAssessedParts'] }}</td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black;">Less Excess (-) :</td>
                <td style="border: 1px solid black; text-align: right;">{{ $summaryTableResult['lessExcess'] ?? 0 }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black; font-weight: bold;">TOTAL :</td>
                <td style="border: 1px solid black; text-align: right; font-weight: bold;">{{ $summaryTableResult['totalEstimate'] }}</td>
                <td style="border: 1px solid black;"></td>
                <td style="border: 1px solid black; font-weight: bold;">TOTAL :</td>
                <td style="border: 1px solid black; text-align: right; font-weight: bold;">{{ $summaryTableResult['totalAssessed'] }}</td>
                <td style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid black; font-weight: bold; text-align: left;">
                    Hence, the Net Loss assessment comes to = {{ $summaryTableResult['totalAssessed'] }} /- INR
                </td>
            </tr>
            <tr>  
                <td colspan="6" style="border: 1px solid black; font-weight: bold; text-align: left;">
                    <i>({{ ucfirst(convertNumberToWords($summaryTableResult['totalAssessed'] ?? '')) }} Only)</i>
                </td>
            </tr>
            <tr>  
                <td colspan="6" style="border: 1px solid black; text-align: left;">
                    The Repair Bill/Invoice is attached. This report is issued without bias and prejudice.
                </td>
            </tr>
        </tbody>
    </table>

    <!-- REMARKS SECTION -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr><td colspan="8" style="border: 1px solid black; font-weight: bold; text-align: left;"><strong>REMARKS</strong></td></tr>
        <tr><td colspan="8" style="border: 1px solid black; text-align: left;">  1. The damages as observed were found to be fresh and consistent with the nature of accident as reported.
        The loss was proximately caused by an insured peril, and none of the exclusions under the policy had operated to bring about the loss.</td></tr>
        <tr><td colspan="8" style="border: 1px solid black; text-align: left;"> 2. The R.C. and the D.L. were verified from the original documents and found in order.
            The photocopies of the same are enclosed with this survey report.
            <strong>Repair Bill Received on Dt.</strong></td></tr>
        <tr><td colspan="8" style="border: 1px solid black; text-align: left;"><strong>3. Payment to Insured.</strong></td></tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="3" style="border: 1px solid black; font-weight: bold; text-align: left;"><i>Enclosed please find here with:</i></td>
        </tr>
        <tr><td colspan="3" style="border: 1px solid black; text-align: left;">1. Digital Photographs 08 nos.</td></tr>
        <tr><td colspan="3" style="border: 1px solid black; text-align: left;">2. Loss Assessment Fee bill in duplicate.</td></tr>
        <tr><td colspan="3" style="border: 1px solid black; text-align: left;">3. Claim form and Estimate.</td></tr>
        <tr><td colspan="3" style="border: 1px solid black; text-align: left;">4. Copies of the vehicular documents duly verified from original as produced.</td></tr>
        <tr><td colspan="3" style="border: 1px solid black; text-align: left;">5. Repair Invoice.</td></tr>
    </table>
    @php
        $path = public_path('storage/logo/signature.png');
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
