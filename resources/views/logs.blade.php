<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .log-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .log-timestamp {
            color: #666;
            font-weight: bold;
            margin-right: 10px;
        }

        .log-level {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 10px;
        }

        .log-level.debug {
            background-color: #d3d3d3;
            color: #333;
        }

        .log-level.info {
            background-color: #add8e6;
            color: #000;
        }

        .log-level.notice {
            background-color: #90ee90;
            color: #000;
        }

        .log-level.warning {
            background-color: #ffcc00;
            color: #000;
        }

        .log-level.error {
            background-color: #ff6666;
            color: #fff;
        }

        .log-level.critical {
            background-color: #ff0000;
            color: #fff;
        }

        .log-level.alert {
            background-color: #ff4500;
            color: #fff;
        }

        .log-level.emergency {
            background-color: #8b0000;
            color: #fff;
        }

        .log-message {
            margin-left: 20px;
            color: #333;
        }
    </style>

</head>
<body>
<h1>Log Viewer</h1>
<hr>
@if (count($logs) > 0)
    @foreach ($logs as $log)
        <div class="log-container">
            <span class="log-timestamp">{{ $log['timestamp'] }}</span>
            <span class="log-level {{ strtolower($log['level']) }}">{{ $log['level'] }}</span>
            <span class="log-message">{{ $log['message'] }}</span>
        </div>
    @endforeach
@else
    <p>No logs found.</p>
@endif
<a id="pointToScrollTo"></a>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const target = document.getElementById('pointToScrollTo');
        if (target) {
            target.scrollIntoView({behavior: 'smooth'});
        }
    });
</script>
</body>
</html>
