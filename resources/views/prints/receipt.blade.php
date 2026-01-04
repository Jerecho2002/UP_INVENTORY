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

        <p><strong>Item:</strong>
            {{ $item->inventoryItems->item_name ?? 'N/A' }}
        </p>

        <p><strong>Property No:</strong>
            {{ $item->inventoryItems->property_number ?? 'N/A' }}
        </p>

        <p><strong>Accountable Person:</strong>
            {{ $item->acknowledgementReceipts->accountablePerson->full_name ?? 'N/A' }}
        </p>

        <p><strong>Issued By:</strong>
            {{ $item->acknowledgementReceipts->issuedBy->email ?? 'N/A' }}
        </p>

        <hr>

    @endforeach

</body>

</html>