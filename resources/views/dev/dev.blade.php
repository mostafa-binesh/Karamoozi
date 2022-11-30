<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DEV TOOL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        * {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class=" text-center">DEV TOOL</h2>
        @if (isset($messages))
            <div class="card">
                {{ $messages }}
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </div>
        @endif
        @if (isset($errors))
            <div class="card">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <label class=" text-center pb-3" for="">تایید کردن سرپرست صنعت</label>
                    <form method="post" action="{{ route('devTool') }}">
                        @csrf
                        <input type="hidden" name="verifyIndustrySupervisor" value="1">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="نام کاربری" name="in">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> --}}
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <label class=" text-center pb-3" for="">عدم تایید سرپرست صنعت</label>
                    <form method="post" action="{{ route('devTool') }}">
                        @csrf
                        <input type="hidden" name="unverifyIndustrySupervisor" value="1">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="نام کاربری" name="in">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> --}}
                        <button name="form1" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <label class=" text-center pb-3" for="">حذف سرپرست یک دانشجو</label>
                    <form method="post" action="{{ route('devTool') }}">
                        @csrf
                        <input type="hidden" name="deleteIndustryOfStudent" value="1">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="exampleInputEmail2"
                                aria-describedby="emailHelp" placeholder="شماره دانشجویی" name="in">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> --}}
                        <button name="form2" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <label class=" text-center pb-3" for="">تغییر وضعیت دانشجو به ارزیابی نشده</label>
                    <form method="post" action="{{ route('devTool') }}">
                        @csrf
                        <input type="hidden" name="unevaluateStudent" value="1">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="exampleInputEmail2"
                                aria-describedby="emailHelp" placeholder="شماره دانشجویی" name="in">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> --}}
                        <button name="form2" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <label class=" text-center pb-3" for="">تغییر وضعیت دانشجو به ارزیابی شده</label>
                    <form method="post" action="{{ route('devTool') }}">
                        @csrf
                        <input type="hidden" name="evaluateStudent" value="1">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="exampleInputEmail2"
                                aria-describedby="emailHelp" placeholder="شماره دانشجویی" name="in">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> --}}
                        <button name="form2" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <p>sadsasa</p>
                    {{-- <label class=" text-center pb-3" for="">تبدیل دانشجو به ارزیابی شده</label>
                <form method="post" action="{{ route('devTool') }}">
                    @csrf
                    <input type="hidden" name="evaluateStudent" value="1">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">نام کاربری</label>
                        <input type="text" class="form-control" id="exampleInputEmail2" aria-describedby="emailHelp"
                            placeholder="شماره دانشجویی" name="in">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    {{-- <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> 
                    <button name="form2" type="submit" class="btn btn-primary">Submit</button>
                </form> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <p>sadsasa</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <p>sadsasa</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 m-2">
                    <p>sadsasa</p>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    {{-- </div> --}}
</body>

</html>
