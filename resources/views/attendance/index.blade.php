<!DOCTYPE html>
<html>
<head>
    <title>Attendance List</title>
</head>
<body>
<h1>Attendance Records</h1>
<table>
    <thead>
    <tr>
        <th>User ID</th>
        <th>Timestamp</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($attendances as $attendance)
        <tr>
            <td>{{ $attendance['user_id'] }}</td>
            <td>{{ $attendance['timestamp'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
