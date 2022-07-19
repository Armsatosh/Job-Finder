<body>
<div class="bg-black d-flex p-2">
    <div class="container">
        <h1 class="ax-center text-white text-center mb-10">Congratulations {{$user->name}} you are successfully registered</h1>
        <img class="ax-center max-w-56 mb-10 rounded-lg"src="{{$message->embed(public_path('images/job-finder.png'))}}">
        <p class="ax-center max-w-96 lh-lg text-white text-center text-2xl mb-10">
            Your Login is: {{$user->email}} <br>
            Your password is: {{$user->password}}
        </p>
        <h4>
            To visit our site please click in this link <br>
            <a class="btn btn-yellow-300 rounded-full fw-800 text-5xl py-4 ax-center mb-10 w-full w-lg-80" href="https://www.jobFinder.com/">www.jobFinder.com</a>
        </h4>
    </div>
</div>
<div class="container">
    <div class="text-muted text-center my-6">
        Sent with <3 from Hip Corp.<br>
        Hip Corp. 1 Hip Street<br>
        Gnarly State, 01234 USA <br>
    </div>
</div>
</body>