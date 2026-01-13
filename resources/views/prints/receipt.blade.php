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
            max-width: 120px;
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
        .purchase-info {
            border: 1px solid #000;
            border-top: none;
            font-size: 10.5pt;
            min-height: 45mm;
            /* 🔑 THIS FIXES IT */
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
                </div>

                <div class="header-right">
                    <img src="images/uplogo-1.png" alt="Unit Logo">
                </div>
            </div>

            <!-- TITLE -->
            <div class="title-header">
                <h1>INVENTORY CUSTODIAN SLIP</h1>
            </div>

            <!-- META -->
            <div class="meta">
                <div class="meta-row">
                    <div><strong>Entity Name:</strong> ITC Office</div>
                    <div><strong>ICS No.:</strong> 205-259-250-295</div>
                </div>
                <div class="meta-row">
                    <div><strong>Fund Cluster:</strong>250292</div>
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