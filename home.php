<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System Home</title>
    <style>
        /* Style for loading overlay */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none; /* Hidden by default */
            flex-direction: column;
        }
        
        /* Style for the spinner */
        .spinner {
            border: 8px dotted #333;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #333;
        }
        
        /* Spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Attendance System</h1>
    <div style="text-align: center; margin-bottom: 20px;">
        <form action="habitual_late.php" method="POST" onsubmit="showLoading()">
            <!-- Date range inputs -->
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start_date" required>
            
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end_date" required>
            
            <!-- Late time input -->
            <label for="late-time">Late Time Limit:</label>
            <input type="time" id="late-time" name="late_time" value="09:30" required>
            
            <button type="submit" style="padding: 10px 20px; font-size: 16px;">Generate Monthly Late Report</button>
        </form>
    </div>

    <!-- Loading overlay -->
    <div id="loading-overlay">
        <div class="spinner" id="spinner">
            <span id="timer">0</span>s
        </div>
        <p>Loading...</p>
    </div>

    <script>
        let timerInterval;
        let seconds = 0;

        // Function to show loading overlay and start timer
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
            startTimer();
        }

        // Function to start the timer
        function startTimer() {
            timerInterval = setInterval(() => {
                seconds++;
                document.getElementById('timer').textContent = seconds;
            }, 1000);
        }

        // Function to stop the timer (if needed)
        function stopTimer() {
            clearInterval(timerInterval);
        }
    </script>
</body>
</html>
