<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Inventory Custodian Slip</title>

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

        .money {
            font-family: DejaVu Sans, sans-serif;
        }


        /* A4 PAGE */
        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #fff;
            position: relative;
            overflow: hidden;
            outline: 1px solid #000;
            /* ✅ USE OUTLINE, NOT BORDER */
        }

        /* CONTENT */
        .content-area {
            padding: 15mm 20mm 32mm 20mm;
            /* 🔽 reduced bottom */
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 6mm;
            /* 🔽 */
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
            /* 🔽 logo size */
        }

        .header-center h1 {
            font-size: 14pt;
            font-weight: bold;
        }

        .header-center p {
            font-size: 10pt;
        }

        .header-center h2 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* TITLE */
        .title-header {
            text-align: center;
            margin: 6mm 0 10mm 0;
            /* space above & below */
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

        /* JUSTIFY END (RIGHT COLUMN) */
        .meta-row div:last-child {
            text-align: right;
        }


        /* TABLE */
        .ics-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
            /* margin-bottom: 5mm; */
            /* 🔽 */
        }

        .ics-table th,
        .ics-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        .ics-table td:nth-child(5) {
            text-align: left;
            padding-left: 5px;
        }

        /* PURCHASE INFO */
        .purchase-section {
            width: 100%;
        }

        /* TABLE */
        .purchase-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .purchase-table td {
            border: 1px solid #000;
            border-top: none;
            height: 35 mm;
            padding: 6px;
            vertical-align: top;
        }

        /* FLEX INSIDE THE CELL */
        .purchase-info {
            display: flex;
            flex-direction: column;
            gap: 2mm;
        }

        .purchase-info .label {
            font-weight: bold;
        }

        .purchase-info .value {
            margin-left: 0;
        }

        .purchase-info>div {
            display: flex;
            gap: 2mm;
        }

        .purchase-info h2 {
            font-size: 10.5pt;
        }

        .puchase-first-line {
            margin-bottom: 1em;
        }

        .purchase-second-line {
            margin-bottom: 1em;

        }

        .purchase-right-content {
            padding: 25px;
        }

        .purchase-right-one {
            margin-bottom: 3em;
        }

        /* SIGNATURE */
        .signature-section {
            position: absolute;
            display: flex;
            margin-top: -8.3em;
            /* bottom: 10mm; */
            /* 🔽 pulled up */
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

        /* PRINT SAFETY */
        @media print {
            body {
                margin: 0;
            }

            * {
                page-break-inside: avoid !important;
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
                        <div>
                            <strong>Entity Name:</strong>
                            {{ $firstItem->item_name ?? 'N/A' }}
                        </div>
                        <div>
                            <strong>ICS No.:</strong>
                            {{ $firstItem->property_number ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="meta-row">
                        <div>
                            <strong>Fund Cluster:</strong>
                            {{ $receiptItems->first()->inventoryItems->fund_source ?? 'N/A' }}
                        </div>
                        <div>
                            <strong>Date:</strong>
                            {{ optional($receipt->created_at)->format('m/d/Y') }}
                        </div>
                    </div>
                </div>

                <!-- ITEMS TABLE -->
                <table class="ics-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Quantity</th>
                            <th rowspan="2">Unit</th>
                            <th colspan="2">Amount</th>
                            <th rowspan="2">Description</th>
                            <th rowspan="2">Inventory Item No.</th>
                            <th rowspan="2">Estimated Useful Life</th>
                        </tr>
                        <tr>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($receiptItems as $item)
                            <tr>
                                <td>{{ $item->inventoryItems->quantity ?? 1 }}</td>
                                <td>{{ $item->inventoryItems->unit ?? 'unit' }}</td>
                                <td><span class="money">₱</span>{{ number_format($item->inventoryItems->unit_cost, 2) }}</td>
                                <td><span class="money">₱</span>{{ number_format($item->inventoryItems->unit_cost * ($item->inventoryItems->quantity ?? 1), 2) }}
                                </td>
                                <td>{{ $item->inventoryItems->item_name }}</td>
                                <td>{{ $item->inventoryItems->property_number }}</td>
                                <td>{{ $item->inventoryItems->useful_life ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- PURCHASE INFO -->
                <div class="purchase-section">
                    <table class="purchase-table">
                        <tr>
                            <td width="60%">
                                <div class="purchase-info">
                                    <div>
                                        <span class="label">Purchase From:</span>
                                        <span class="value">
                                            {{ $item->inventoryItems->supplier->supplier_name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="label">Invoice No.:</span>
                                        <span class="value">{{ $item->inventoryItems->invoice ?? 'N/A' }}</span>
                                    </div>

                                    <div>
                                        <span class="label">PO No.:</span>
                                        <span class="value">{{ $item->inventoryItems->po_number ?? 'N/A' }}</span>
                                    </div>

                                    <div>
                                        <span class="label">PR No.:</span>
                                        <span class="value">{{ $item->inventoryItems->pr_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td width="40%">
                                <div class="purchase-info">
                                    <div>
                                        <span class="label">Serial No.:</span>
                                        <span class="value">{{ $item->inventoryItems->serial_number ?? 'N/A' }}</span>
                                    </div>

                                    <div>
                                        <span class="label">Location:</span>
                                        {{ $receipt->accountablePerson->department ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
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
