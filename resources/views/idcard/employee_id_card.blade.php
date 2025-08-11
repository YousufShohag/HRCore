<!DOCTYPE html>
<html>
<head>
    <style>
        .id-card {
            width: 350px;
            height: 200px;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 15px;
            font-family: Arial, sans-serif;
            position: relative;
            background: #f0f0f0;
        }
        .photo {
            width: 100px;
            height: 120px;
            border: 1px solid #333;
            float: left;
            margin-right: 15px;
            object-fit: cover;
        }
        .details {
            float: left;
            width: 300px;
        }
        .details h2 {
            margin: 0 0 10px 0;
            font-size: 17px;
        }
        .details p {
            margin: 4px 0;
            font-size: 12px;
        }
        .footer {
            position: absolute;
            bottom: 10px;
            font-size: 12px;
            width: 100%;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="id-card">
        {{-- Check if employee has image --}}
        @php
            $imagePath = $employee->image 
                ? public_path('storage/' . $employee->image) 
                : public_path('images/default-user.png');
        @endphp
        <img src="{{ $imagePath }}" class="photo" alt="Employee Photo" />

        <div class="details">
            <h2>{{ $employee->name ?? 'No Name' }}</h2>
            <p><strong>ID:</strong> {{ $employee->employee_code ?? 'EMP' . $employee->id }}</p>
            <p><strong>Designation:</strong> {{ $employee->designation ?? 'N/A' }}</p>
            <p><strong>Department:</strong> {{ $employee->department ?? 'N/A' }}</p>
            <p><strong>Join Date:</strong> {{ $employee->join_date ? $employee->join_date->format('M d, Y') : 'N/A' }}</p>
        </div>
        <div class="footer">
            Company Name - Employee ID Card
        </div>
    </div>
</body>
</html>
