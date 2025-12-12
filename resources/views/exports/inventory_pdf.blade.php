<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            width: 80px;
            margin-bottom: 5px;
        }
        .header h2 {
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #444;
            padding: 5px;
            text-align: left;
        }
        .footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ $logo }}" alt="USEP Logo">
    <div>
        <strong>University of Southeastern Philippines</strong><br>
        Office of the Student Affairs and Services<br>
        Tagum-Mabini Campus
    </div>
    <h2>Inventory Report - {{ $year }}</h2>
</div>

<table>
    <thead>
        <tr>
            <th>Submission ID</th>
            <th>Title</th>
            <th>Authors</th>
            <th>Adviser</th>
            <th>Program</th>
            <th>Academic Year</th>
            <th>Inventory No.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inventories as $item)
        <tr>
            <td>{{ $item->submission_id }}</td>
            <td>{{ $item->title }}</td>
            <td>{{ $item->authors }}</td>
            <td>{{ $item->adviser }}</td>
            <td>{{ $item->program->name ?? '—' }}</td>
            <td>{{ $item->academic_year }}</td>
            <td>{{ $item->inventory_number }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Exported: {{ $timestamp }}
</div>

</body>
</html>
