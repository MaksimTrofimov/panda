<?php echo __DIR__  ?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
    <form>
    <div class="form-group">
        <label for="exampleFormControlFile1">Example file input</label>
        <input type="file" class="form-control-file" id="exampleFormControlFile1">
        <!-- <a href="#" class="submit button">Загрузить файлы</a> -->
    </div>
    </form>
    <div id="img" hidden="hidden">
        <img id='original' src="" alt="Girl in a jacket">
        <img id='modified' src="" alt="Girl in a jacket">
    </div>

<script>
    $('input[type=file]').change(function(){
        var file_data = $('#exampleFormControlFile1').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        $.ajax({
            url: '/upload.php',
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function ($data) {
                data = JSON.parse($data);
                if (data.status) {
                    $('#original').attr('src', data.urlOriginal);
                    $('#modified').attr('src', data.urlModified);
                    $('#img').show();
                }
            }
        });
    });
</script>
</body>
</html>
