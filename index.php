<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Card Layout with Bootstrap 5</title>
    <style>
        .status-store {
            background-color: #4caf50; /* Green */
            color: white;
        }
        .status-measure {
            background-color: #2196f3; /* Blue */
            color: white;
        }
        .status-for-ncu {
            background-color: #ff9800; /* Orange */
            color: white;
        }
        .status-sem {
            background-color: #9c27b0; /* Purple */
            color: white;
        }
        .status-nuce {
            background-color: #e91e63; /* Pink */
            color: white;
        }
        .status-pending {
            background-color: #f44336; /* Red */
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-store">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-05</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: Store</h6>
                            <p class="card-text">This is a simple card with a date and a subtitle.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-measure">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-06</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: MEASURE</h6>
                            <p class="card-text">This is another card with more details.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-for-ncu">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-07</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: For NCU</h6>
                            <p class="card-text">This card includes some additional content.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Card 4 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-sem">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-08</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: SEM</h6>
                            <p class="card-text">This card is marked with SEM status.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Card 5 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-nuce">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-09</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: NUCE</h6>
                            <p class="card-text">This card is marked with NUCE status.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Card 6 -->
            <div class="col-md-4 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card status-pending">
                        <div class="card-body">
                            <h5 class="card-title">Date: 2024-12-10</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Sub title: Pending</h6>
                            <p class="card-text">This card is marked as Pending.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
