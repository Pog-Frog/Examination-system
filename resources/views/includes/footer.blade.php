<footer class="page-footer dark">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5>Get started</h5>
                <ul>
                    <li><a href="{{ Route('index')}}">Home</a></li>
                    <li><a href="{{Route('student_register')}}">Sign up</a></li>
                    
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>About us</h5>
                <ul>
                    <li><a href="{{Route('iamteacher')}}">Are You A Teacher</a></li>
                    <li><a href="{{Route('iamstudent')}}">Are You A Student</a></li>
                    <li><a href="#">About The System</a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>Support</h5>
                <ul>
                    <li><a href="{{Route('contactUs')}}">ContactUs</a></li>
                   
                </ul>
            </div>
            
        </div>
    </div>
    <div class="footer-copyright">
        <p>© {{ now()->year }} Copyright Quizzix</p>
    </div>
</footer>