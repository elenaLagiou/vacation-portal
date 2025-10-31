<h1>Create Vacation Request</h1>
<form action="/employee/create" method="POST">
    <label>Start Date:</label>
    <input type="date" name="start_date" required><br>

    <label>End Date:</label>
    <input type="date" name="end_date" required><br>

    <label>Reason:</label>
    <textarea name="reason" required></textarea><br>

    <button type="submit">Submit Request</button>
</form>
<a href="/employee/home">Back</a>