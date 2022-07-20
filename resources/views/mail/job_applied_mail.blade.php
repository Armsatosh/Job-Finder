<body>
<div class="bg-black d-flex p-2">
    <div class="container">
        <h1 class="ax-center text-white text-center mb-10">You got a new response to your job vacancy</h1>
        <img class="ax-center max-w-56 mb-10 rounded-lg"src="{{$message->embed(public_path('images/job-finder.png'))}}">
        <h3 class="ax-center max-w-96 lh-lg text-white text-center text-2xl mb-10">
            Job vacancy title: {{$data['title']}} <br>
            Applicant Name: {{$data['applicantName']}}<br>
            Response Count: {{$data['appliedCount']}}<br>
            Response Date: {{$data['appliedDate']}}<br>
        </h3>
        <h4>
            To see your responses plase visit our site <br>
            <a class="btn btn-yellow-300 rounded-full fw-800 text-5xl py-4 ax-center mb-10 w-full w-lg-80" href="https://www.jobFinder.com/">www.jobFinder.com</a>
        </h4>
    </div>
</div>
<div class="container">
    <div class="text-muted text-center my-6">
        Gnarly State, 01234 USA <br>
    </div>
</div>
</body>