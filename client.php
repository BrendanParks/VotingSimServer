<html>
<head>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

</head>
<body>
<div id="fb-root"></div>
<script>
var Player = null;
  var fb_response = new Object();
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '399558776852663',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      testAPI(fb_response);
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login(function(response) {
       // handle the response
     }, {scope: 'email,user_likes'});
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      FB.login(function(response) {
         // handle the response
       }, {scope: 'email,user_likes'});
    }
  });
  };

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

  // Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function testAPI(fb_response) {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me?fields=id,name,first_name,last_name,link,username,gender,locale,age_range,birthday,email,picture,bio,hometown,friends', function(response) {
      
      console.log('Good to see you, ' + response.name + '.');
      console.log(response);
      
      facebook_info = new Object();

      facebook_info.status = response.status;
      facebook_info.name = response.name;
      facebook_info.first_name = response.first_name;
      facebook_info.last_name = response.last_name;
      facebook_info.username = response.username;
      facebook_info.id = response.id;
      FB.getLoginStatus(function (response) {
            if (response.authResponse) {
                facebook_info.access_token = response.authResponse.accessToken;
                alert(facebook_info.access_token);
            }
        });
      fb_response = facebook_info;
      $.ajax({
        type: "POST",
        url: "https://main-securityproject.rhcloud.com/api_cla.php/add_voter",
        async: false,
        dataType: "json",
        data: { token: fb_response.access_token },
        success: function(data){
            console.log("data");
            console.log(data);
        }
        })
      .done(function( msg ) {
        console.log( "msg" );
        console.log( msg );
        // console.log( Player );
      })
      .fail(function(err) {
        console.log("err");
        console.log( err);
        });

    });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses the JavaScript SDK to
  present a graphical Login button that triggers the FB.login() function when clicked. -->

<fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>

<form name="vote" action="">
<input type="radio" name="sex" value="male">Candidate 1<br>
<input type="radio" name="sex" value="male">Candidate 2<br>
<input type="radio" name="sex" value="male">Candidate 3<br>
<input type="radio" name="sex" value="male">Candidate 4<br>
<input type="radio" name="sex" value="male">Candidate 5
<input type="submit" value="Submit">
</form>

<button>View results</button>

</body>
</html>