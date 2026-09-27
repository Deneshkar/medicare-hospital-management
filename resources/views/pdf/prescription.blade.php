<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; }
        .info-row { margin-bottom: 6px; }
        .label { font-weight: bold; display: inline-block; width: 120px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MediCare</h1>
        <p>Prescription</p>
    </div>

    <div class="info-row"><span class="label">Patient:</span> {{ $prescription->patient->user->name }}</div>
    <div class="info-row"><span class="label">Doctor:</span> Dr. {{ $prescription->doctor->user->name }}</div>
    <div class="info-row"><span class="label">Date:</span> {{ $prescription->created_at->format('F j, Y') }}</div>

    @if($prescription->general_instructions)
        <div class="info-row"><span class="label">Instructions:</span> {{ $prescription->general_instructions }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->items as $item)
                <tr>
                    <td>{{ $item->medicine_name }}</td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->frequency }}</td>
                    <td>{{ $item->duration }}</td>
                    <td>{{ $item->instructions ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
