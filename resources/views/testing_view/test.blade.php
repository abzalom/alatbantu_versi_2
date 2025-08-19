<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" href="/assets/img/mamberamo_raya.ico" type="image/x-icon">

    <link rel="stylesheet" href="/vendors/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendors/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="stylesheet" href="/vendors/select2-4.0.rc/css/select2.min.css">
    <link rel="stylesheet" href="/vendors/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="/vendors/summernote-0.8.18-dist/summernote.min.css">
    <title>Testing View</title>

</head>

<body>
    <div class="container">
        <button class="btn btn-primary" id="testButton" data-rap='@json($rap)'>{{ $rap->text_subkegiatan }}</button>
    </div>
    <script src="/vendors/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#testButton').on('click', function() {
                var rap = $(this).data('rap');
                console.log(rap);
            });
        });
    </script>
</body>

</html>
