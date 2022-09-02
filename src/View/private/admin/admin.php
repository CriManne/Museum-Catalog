<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid my-5 px-5">

        <form class="input-group mb-3" id="search-form">
            <input type="text" class="form-control" placeholder="Search user" aria-label="User's search" id="user-search" required>
            <input type="submit" class="btn btn-outline-secondary">
        </form>

        <div class="container-fluid d-flex flex-column p-0 gap-2 align-items-center" id="result-container">
            <div class="container-fluid" id="tb-container"></div>
            <nav id="navigation">
                <ul class="pagination">
                    <li class="page-item" id="navigation-prev">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <div id="paginations" class="d-flex flex-row">
                        <li class="page-item" id="current-page-index"><a class="page-link" href="#">1</a></li>
                    </div>

                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <select class="form-select w-auto" aria-label="Select per page limit" id="page-limit">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
            </select>
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        <?php include('script.js'); ?>
    </script>
</body>

</html>