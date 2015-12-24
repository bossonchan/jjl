/*TMODJS:{"version":1,"md5":"4efab53df5d564bb43eef6adcc408a45"}*/
template('signup',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,blocks=$data.blocks,$value=$data.$value,$index=$data.$index,$escape=$utils.$escape,$out='';$out+='<div class="container"> <form class="form-signin"> <h2 class="form-signin-heading">Sign Up</h2> <label for="inputUsername" class="sr-only">inputUsername</label> <input type="text" id="inputUsername" class="form-control" placeholder="User Name" required="" autofocus=""> <label for="inputPassword" class="sr-only">Password</label> <input type="password" id="inputPassword" class="form-control" placeholder="Password" required=""> <label for="inputConfirm" class="sr-only">Confirm</label> <input type="password" id="inputConfirm" class="form-control" placeholder="Confirm" required=""> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <span id="selected-block">Select Block</span> <span class="caret"></span> </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenu1"> ';
$each(blocks,function($value,$index){
$out+=' <li class="js-select-block"><a data-id="';
$out+=$escape($value);
$out+='" href="javascript:void(0)">Block';
$out+=$escape($value);
$out+='</a></li> ';
});
$out+=' </ul> </div> <button class="btn btn-success btn-block js-signup" >Sign up</button> <a href="javascript:void(0)" class=\'js-back-login\'>&lt;&lt;&lt;back login in</a> </form> </div> <script src=\'./js/login.js\'></script> ';
return new String($out);
});