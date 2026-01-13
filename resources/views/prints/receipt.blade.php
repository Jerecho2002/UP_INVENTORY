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

        /* A4 PAGE */
        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #fff;
            position: relative;
            border: 1px solid #000;
        }

        /* CONTENT */
        .content-area {
            padding: 15mm 20mm 45mm 20mm;
            /* reserve space for signature */
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8mm;
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
            max-width: 120px;
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

        /* META */
        .meta {
            font-size: 10.5pt;
            margin-bottom: 6mm;
        }

        .meta-row {
            display: table;
            width: 100%;
        }

        .meta-row div {
            display: table-cell;
            width: 50%;
        }

        /* TABLE */
        .ics-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
            margin-bottom: 6mm;
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

        /* COLUMN WIDTHS */
        .col-1 {
            width: 7%;
        }

        .col-2 {
            width: 7%;
        }

        .col-3 {
            width: 12%;
        }

        .col-4 {
            width: 12%;
        }

        .col-5 {
            width: 28%;
        }

        .col-6 {
            width: 17%;
        }

        .col-7 {
            width: 17%;
        }

        /* PURCHASE INFO */
        .purchase-info {
            border: 1px solid #000;
            padding: 6mm;
            font-size: 10.5pt;
            margin-bottom: 6mm;
        }

        .purchase-wrapper {
            display: grid;
            grid-template-columns: 35% 65%;
            column-gap: 10mm;
        }

        .purchase-left {
            display: flex;
            flex-direction: column;
            gap: 6mm;
        }

        .purchase-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            row-gap: 4px;
            column-gap: 10px;
        }

        .purchase-item {
            display: flex;
        }

        .purchase-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .purchase-value {
            margin-left: 5px;
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
            height: 30mm;
            padding: 8px;
            vertical-align: top;
        }

        /* PRINT */
        @media print {
            body {
                margin: 0;
            }

            .a4-page {
                page-break-after: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="a4-page">
        <div class="content-area">

            <!-- HEADER -->
            <div class="header">
                <div class="header-left">
                    <img src="images/uplogo-2.png" alt="UP Logo">
                </div>

                <div class="header-center">
                    <h1>University of the Philippines</h1>
                    <p>Region VII - Central Visayas</p>
                    <h2>INVENTORY CUSTODIAN SLIP</h2>
                </div>

<<<<<<< HEAD
       <table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Property Number</th>
            <th>Accountable Person</th>
            <th>Issued By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($acknowledgementItems as $item)
            <tr>
                <td>{{ $item->inventoryItems->item_name ?? 'N/A' }}</td>
                <td>{{ $item->inventoryItems->property_number ?? 'N/A' }}</td>
                <td>{{ $item->acknowledgementReceipts->accountablePerson->full_name ?? 'N/A' }}</td>
                <td>{{ $item->acknowledgementReceipts->issuedBy->email ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
=======
                <div class="header-right">
                    <img src="images/uplogo-1.png" alt="Unit Logo">
                </div>
            </div>

            <!-- META -->
            <div class="meta">
                <div class="meta-row">
                    <div><strong>Entity Name:</strong> ITC Office</div>
                    <div><strong>ICS No.:</strong> 205-259-250-295</div>
                </div>
                <div class="meta-row">
                    <div><strong>Fund Cluster:</strong></div>
                    <div><strong>Date:</strong> 18/2026</div>
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <table class="ics-table">
                <thead>
                    <tr>
                        <th class="col-1" rowspan="2">Quantity</th>
                        <th class="col-2" rowspan="2">Unit</th>
                        <th class="col-3" colspan="2">Amount</th>
                        <th class="col-5" rowspan="2">Description</th>
                        <th class="col-6" rowspan="2">Inventory Item No.</th>
                        <th class="col-7" rowspan="2">Estimated Useful Life</th>
                    </tr>
                    <tr>
                        <th class="col-3">Unit Cost</th>
                        <th class="col-4">Total Cost</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>unit</td>
                        <td>10,000</td>
                        <td>10,000</td>
                        <td>Testtesttest</td>
                        <td>56</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>unit</td>
                        <td>10,000</td>
                        <td>10,000</td>
                        <td>Testtesttest test</td>
                        <td>56</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>unit</td>
                        <td>10,000</td>
                        <td>10,000</td>
                        <td>Testtesttest test</td>
                        <td>56</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- PURCHASE INFORMATION -->
            <div class="purchase-info">
                <div class="purchase-wrapper">
                    <!-- LEFT -->
                    <div class="purchase-grid">
                        <div class="purchase-item">
                            <div class="purchase-label">Purchase From:</div>
                            <div class="purchase-value">Testtesttest</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">Invoice No.:</div>
                            <div class="purchase-value">2502</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">Date:</div>
                            <div class="purchase-value">1/10/2026</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">PO No.:</div>
                            <div class="purchase-value">203-4024-184</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">PO Date:</div>
                            <div class="purchase-value">1/10/2026</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">PR No.:</div>
                            <div class="purchase-value">203-259-249</div>
                        </div>
                    </div>
>>>>>>> modifier

                    <!-- RIGHT -->
                    <div class="purchase-left">
                        <div class="purchase-item">
                            <div class="purchase-label">Serial No.:</div>
                            <div class="purchase-value">_____________________</div>
                        </div>
                        <div class="purchase-item">
                            <div class="purchase-label">Location:</div>
                            <div class="purchase-value">_____________________</div>
                        </div>
                    </div>


                </div>
            </div>

        </div>

        <!-- SIGNATURE -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td width="50%">
                        <strong>Received From:</strong><br><br>
                        ___________________________<br>
                        Signature over Printed Name
                    </td>
                    <td width="50%">
                        <strong>Received By:</strong><br><br>
                        ___________________________<br>
                        Signature over Printed Name
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>