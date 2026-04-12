<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Skillio</title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
padding:20px;
background:linear-gradient(135deg,#f5f7fa 0%,#e4e8f0 100%);
}

.login-container{
display:flex;
flex-direction:column;
align-items:center;
width:100%;
max-width:420px;
}

.logo-container{
text-align:center;
margin-bottom:25px;
}

.logo-img{
width:140px;
height:auto;
margin-bottom:10px;
}

.logo-text{
font-size:2.2rem;
font-weight:700;
color:#2d3748;
margin-bottom:5px;
}

.tagline{
font-size:1rem;
color:#4a5568;
margin-bottom:10px;
}

.login-form{
background-color:white;
background-image:url("{{ asset('images/Skillio.jpeg') }}");
background-repeat:no-repeat;
background-position:center;
background-size:220px;
border-radius:16px;
box-shadow:0 15px 35px rgba(0,0,0,0.1);
padding:40px;
width:100%;
border:1px solid #e2e8f0;
position:relative;
overflow:hidden;
}

/* overlay biar background lembut */
.login-form::before{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:white;
opacity:0.88;
border-radius:16px;
z-index:0;
}

/* konten di atas overlay */
.login-form *{
position:relative;
z-index:1;
}

.form-title{
font-size:1.8rem;
color:#2d3748;
margin-bottom:30px;
text-align:center;
font-weight:600;
}

.input-group{
margin-bottom:25px;
}

.input-label{
display:block;
font-size:0.95rem;
color:#4a5568;
margin-bottom:8px;
font-weight:500;
}

.input-field{
width:100%;
padding:15px 18px;
border:1.5px solid #e2e8f0;
border-radius:10px;
font-size:1rem;
transition:all 0.3s;
background-color:#f8fafc;
}

.input-field:focus{
outline:none;
border-color:#4299e1;
box-shadow:0 0 0 3px rgba(66,153,225,0.15);
background-color:white;
}

.login-button{
width:100%;
padding:16px;
background:linear-gradient(to right,#4299e1,#3182ce);
color:white;
border:none;
border-radius:10px;
font-size:1.05rem;
font-weight:600;
cursor:pointer;
transition:0.3s;
margin-top:10px;
}

.login-button:hover{
transform:translateY(-2px);
}

.error-message{
background-color:#fee2e2;
color:#dc2626;
padding:14px 16px;
border-radius:10px;
margin-top:20px;
font-size:0.95rem;
}

.footer{
margin-top:20px;
text-align:center;
color:#64748b;
font-size:0.85rem;
}
</style>
</head>

<body>

<div class="login-container">

<div class="logo-container">
<div class="logo-text">Skillio</div>
<div class="tagline">UNLOCK YOUR POTENTIAL</div>
</div>

<div class="login-form">
<h2 class="form-title">Login</h2>

<form method="POST" action="{{ url('/login') }}">
@csrf

<div class="input-group">
<label class="input-label">Username</label>
<input type="text" name="username" class="input-field" placeholder="Enter your username" required>
</div>

<div class="input-group">
<label class="input-label">Password</label>
<input type="password" name="password" class="input-field" placeholder="Enter your password" required>
</div>

<button type="submit" class="login-button">Login</button>

</form>

@if(session('error'))
<div class="error-message">
{{ session('error') }}
</div>
@endif

</div>

<div class="footer">
© 2026 Skillio. All rights reserved.
</div>

</div>

</body>
</html>
