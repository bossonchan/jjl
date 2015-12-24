/*TMODJS:{"version":1,"md5":"000502a5b1c294257f3f13f2682e0f53"}*/
template('header',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,username=$data.username,$out='';$out+='<nav class="navbar navbar-inverse navbar-fixed-top"> <div class="container"> <div class="navbar-header"> <a class="navbar-brand" href="./index.html">Neighbour</a> </div> <div id="navbar" class="collapse navbar-collapse"> <ul class="nav navbar-nav"> <li><a href="javascript:void(0)" class=\'js-tab-hoods\'>Hoods</a></li> <li><a href="javascript:void(0)" class=\'js-tab-messages\'>Messages</a></li> <li><a href="javascript:void(0)" class=\'js-tab-post\'>Post</a></li> <li><a href="javascript:void(0)" class=\'js-tab-friends\'>Friends</a></li> </ul> </div> <div class=\'userinfo\'> ';
$out+=$escape(username);
$out+=' <a href="javascript:void(0)" class="js-logout">logout</a> </div> </div> </nav> <script src="./js/header.js"></script> ';
return new String($out);
});