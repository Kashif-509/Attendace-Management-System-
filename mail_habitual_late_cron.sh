#!/bin/bash
set -e

# Set Log file location
log_file="/var/log/mail_monthly_habitual_late.log"

# Log setup with date and time
NOW=$(date +"%d-%m-%Y")
Time=$(date +"%H:%M:%S")
echo "============================$NOW-$Time==============================" >> "$log_file"



# Capture start time in seconds
start_time=$(date +%s)
start_time_formatted=$(date +"%d:%m:%Y %I:%M %p")

echo "Monthly Habitual Late Commers Record fetching started $start_time_formatted" >> "$log_file"

echo ""
# Script executed
/usr/bin/php /var/www/html/mail_monthly_habitual_late.php >> "$log_file" 2>&1

# Capture end time in seconds
end_time=$(date +%s)
end_time_formatted=$(date +"%d:%m:%Y %I:%M %p")

echo "Monthly Habitual Late Commers Record fetched  $end_time_formatted" >> "$log_file"
echo "" >> "$log_file"
echo "" >> "$log_file"
# Calculate execution time
execution_time=$((end_time - start_time))

# Convert execution time to minutes and seconds
execution_minutes=$((execution_time / 60))
execution_seconds=$((execution_time % 60))


# Check if the script executed successfully
if [ $? -eq 0 ]; then
    echo "Execution Time: ${execution_minutes} minutes and ${execution_seconds} seconds." >> "$log_file"
    echo "" >> "$log_file"
    echo "" >> "$log_file"
    echo "Email has been sent along with Monthly Habitual Late Commers record" >> "$log_file"
    echo "" >> "$log_file"
    echo "==============================================$NOW-$Time=======================================" >> "$log_file"
    echo "" >> "$log_file"
else
    echo "Monthly Habitual Late Commers record execution failed! Please try again." >> "$log_file"
    echo "Execution Time: ${execution_minutes} minutes and ${execution_seconds} seconds." >> "$log_file"
fi

