<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Acknowledgement Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>

    <h2>Acknowledgement Receipt</h2>

    @foreach ($acknowledgementItems as $item)

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

    @endforeach

</body>

</html>