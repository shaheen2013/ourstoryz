<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Card</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .event-card {
            width: 300px;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .event-main-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .event-img-small {
            width: 100%;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
        .event-img-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="event-card bg-white ">
        <div style="gap:16px;" class="d-flex align-items-center justify-content-center">
                  <img style="border-radius: 10px;" src="https://via.placeholder.com/70" class=" mb-3" alt="Main Event">
        <div>
          <h5>guest name</h5>
          <p>Date</p>
          </div>
      </div>
        
        <div class="event-img-container">
            <img src="phot_url " class="event-img-small" alt="Event Image 1">
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
