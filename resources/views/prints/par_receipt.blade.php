<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Property Acknowledgement Receipt</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            background: #fff;
            color: #000;
        }

        /* A4 PAGE */
        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            position: relative;
            outline: 1px solid #000;
            overflow: hidden;
        }

        /* CONTENT */
        .content-area {
            padding: 15mm 20mm 45mm 20mm;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 6mm;
            table-layout: fixed;
        }

        .header-left,
        .header-center,
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header-left,
        .header-right {
            width: 25%;
        }

        .header-center {
            width: 50%;
        }

        .header img {
            max-width: 140px;
        }

        .header-center h1 {
            font-size: 14pt;
            font-weight: bold;
        }

        .header-center p {
            font-size: 10pt;
        }

        /* TITLE */
        .title-header {
            text-align: center;
            margin: 6mm 0 10mm;
        }

        .title-header h1 {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* META */
        .meta {
            font-size: 10.5pt;
            margin-bottom: 5mm;
        }

        .meta-row {
            display: table;
            width: 100%;
        }

        .meta-row div {
            display: table-cell;
            width: 50%;
        }

        .meta-row div:last-child {
            text-align: right;
        }

        /* ITEMS TABLE */
        .ics-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .ics-table th,
        .ics-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        /* PURCHASE TABLE */
        .purchase-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .purchase-table td {
            border: 1px solid #000;
            border-top: none;
            height: 28mm;
            padding: 6px;
            vertical-align: top;
        }

        .purchase-info div {
            margin-bottom: 4px;
            padding-left: 5px;
        }

        .label {
            font-weight: bold;
        }

        /* SIGNATURE */
        .signature-section {
            position: absolute;
            bottom: 15mm;
            left: 20mm;
            right: 20mm;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .signature-table td {
            border: 1px solid #000;
            border-top: none;
            height: 26mm;
            padding: 6px;
            vertical-align: top;
        }

        /* PRINT */
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    @foreach ($acknowledgementItems->groupBy('acknowledgement_receipts_id') as $receiptItems)
        @php
            $receipt = $receiptItems->first()->acknowledgementReceipts;
            $firstItem = $receiptItems->first()->inventoryItems;
        @endphp

        <div class="a4-page">
            <div class="content-area">

                <!-- HEADER -->
                <div class="header">
                    <div class="header-left">
                        <img src="{{ public_path('images/uplogo-2.png') }}">
                    </div>

                    <div class="header-center">
                        <h1>University of the Philippines</h1>
                        <p>Region VII - Central Visayas</p>
                    </div>

                    <div class="header-right">
                        <img src="{{ public_path('images/uplogo-1.png') }}">
                    </div>
                </div>

                <!-- TITLE -->
                <div class="title-header">
                    <h1>PROPERTY ACKNOWLEDGEMENT RECEIPT</h1>
                </div>

                <!-- META -->
                <div class="meta">
                    <div class="meta-row">
                        <div><strong>Entity Name:</strong> {{ $firstItem->item_name ?? 'N/A' }}</div>
                        <div><strong>PAR No.:</strong> {{ $firstItem->property_number ?? 'N/A' }}</div>
                    </div>

                    <div class="meta-row">
                        <div><strong>Fund Cluster:</strong> {{ $firstItem->fund_source ?? 'N/A' }}</div>
                        <div><strong>Date:</strong> {{ optional($receipt->created_at)->format('m/d/Y') }}</div>
                    </div>
                </div>

                <!-- ITEMS -->
                <table class="ics-table">
                    <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Description</th>
                            <th>Property No.</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receiptItems as $item)
                            <tr>
                                <td>{{ $item->inventoryItems->quantity ?? 1 }}</td>
                                <td>{{ $item->inventoryItems->unit ?? 'unit' }}</td>
                                <td>{{ $item->inventoryItems->description }}</td>
                                <td>{{ $item->inventoryItems->property_number }}</td>
                                <td>{{ number_format($item->inventoryItems->unit_cost, 2) }}</td>
                                <td>{{ number_format($item->inventoryItems->unit_cost * ($item->inventoryItems->quantity ?? 1), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- PURCHASE INFO -->
                <table class="purchase-table">
                    <tr>
                        <td width="60%">
                            <div class="purchase-info">
                                <div><span class="label">Purchase From:</span>
                                    {{ $firstItem->supplier->supplier_name ?? 'N/A' }}</div>
                                <div><span class="label">Invoice No.:</span> {{ $firstItem->invoice ?? 'N/A' }}</div>
                                <div><span class="label">PO No.:</span> {{ $firstItem->po_number ?? 'N/A' }}</div>
                                <div><span class="label">PR No.:</span> {{ $firstItem->pr_number ?? 'N/A' }}</div>
                                <div><span class="label">Date of Issuance:</div>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>

            <!-- SIGNATURE -->
            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td width="50%">
                            <strong>Received From:</strong><br><br>
                            {{ $receipt->issuedBy->full_name ?? '___________________________' }}<br>
                            Signature over Printed Name
                        </td>
                        <td width="50%">
                            <strong>Received By:</strong><br><br>
                            {{ $receipt->accountablePerson->full_name ?? '___________________________' }}<br>
                            Signature over Printed Name
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>

</html>