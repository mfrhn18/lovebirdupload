<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aplikasi Upload Image</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </head>
    <body>
        <form action="{{ url('drop') }}" method="post" enctype="multipart/form-data">
            @csrf
            <label for="">File(s)</label>
            <input type="file" name="file[]" id="" multiple="true">
            <button type="submit">Upload</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>mime/Type</th>
                    <th>File Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>
                            <a href="{{ url('drop/' . $file->file_name) }}">
                                {{ $file->file_name}}
                            </a>
                        </td>
                        <td>{{ $file->file_type}}</td>
                        <td>
                            {{ number_format($file->file_size / 1024, 1) }} Kb
                        </td>
                        <td>
                            <a href="{{ url('drop/' . $file->file_name . '/download') }}">
                                Download
                            </a>

                            <a href="{{ url('drop/' . $file->id . '/destroy') }}">
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>